<?php

namespace Src\Factura\Application\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Src\Factura\Infrastructure\Requests\StoreFacturaRequest;
use Src\Factura\Infrastructure\Requests\UpdateFacturaRequest;
use Src\Factura\Infrastructure\Resources\FacturaResource;
use Src\Factura\Infrastructure\Models\FacturaEloquentModel;
use Src\Factura\Infrastructure\Models\DetalleFacturaEloquentModel;

class FacturaController extends Controller
{
    public function index()
    {
        $facturas = FacturaEloquentModel::with(['usuario', 'cliente', 'detalles.producto'])->get();
        return FacturaResource::collection($facturas);
    }

    public function store(StoreFacturaRequest $request)
    {
        $validated = $request->validated();
        $validated['usuario_id'] = $request->user()->id;

        $detalles = $validated['detalles'] ?? [];
        unset($validated['detalles']);

        $factura = DB::transaction(function () use ($validated, $detalles) {
            $factura = FacturaEloquentModel::create($validated);

            foreach ($detalles as $detalle) {
                DetalleFacturaEloquentModel::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento' => $detalle['descuento'] ?? 0,
                    'subtotal' => $detalle['subtotal'],
                ]);
            }

            return $factura;
        });

        return new FacturaResource($factura->load(['usuario', 'cliente', 'detalles.producto']));
    }

    public function show(string $id)
    {
        $factura = FacturaEloquentModel::with(['usuario', 'cliente', 'detalles.producto'])->find($id);

        if (!$factura) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }

        return new FacturaResource($factura);
    }

    public function update(UpdateFacturaRequest $request, string $id)
    {
        $factura = FacturaEloquentModel::find($id);

        if (!$factura) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }

        $factura->update($request->validated());
        return new FacturaResource($factura->load(['usuario', 'cliente', 'detalles.producto']));
    }

    public function destroy(string $id)
    {
        $factura = FacturaEloquentModel::find($id);

        if (!$factura) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }

        $factura->detalles()->delete();
        $factura->delete();

        return response()->json([
            'success' => true,
            'message' => 'Factura eliminada exitosamente'
        ], 200);
    }
}
