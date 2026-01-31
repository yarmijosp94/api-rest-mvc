<?php

namespace Src\Factura\Application\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Src\Factura\Infrastructure\Models\FacturaEloquentModel;
use Src\Factura\Infrastructure\Mappers\FacturaMapper;
use Src\Factura\Infrastructure\Requests\StoreFacturaRequest;
use Src\Factura\Infrastructure\Requests\UpdateFacturaRequest;
use Src\Cliente\Infrastructure\Models\ClienteEloquentModel;
use Src\Cliente\Infrastructure\Mappers\ClienteMapper;
use Src\Producto\Infrastructure\Models\ProductoEloquentModel;
use Src\Producto\Infrastructure\Mappers\ProductoMapper;
use Src\Factura\Infrastructure\Models\DetalleFacturaEloquentModel;

class FacturaWebController extends Controller
{
    public function index(): Response
    {
        $facturas = FacturaEloquentModel::with(['cliente', 'detalles'])->get();

        $facturasData = $facturas->map(
            fn($model) => FacturaMapper::toDomain($model)->toArray()
        )->toArray();

        return Inertia::render('Factura/index', [
            'facturas' => [
                'data' => $facturasData,
                'links' => [],
                'meta' => [
                    'total' => count($facturasData),
                    'per_page' => count($facturasData),
                    'current_page' => 1,
                ]
            ],
            'stats' => [
                'total' => count($facturasData),
                'emitidas' => $facturas->where('estado', 'emitida')->count(),
                'pagadas' => $facturas->where('estado', 'pagada')->count(),
                'anuladas' => $facturas->where('estado', 'anulada')->count(),
            ],
        ]);
    }

    public function create(): Response
    {
        $clientes = ClienteEloquentModel::all();
        $productos = ProductoEloquentModel::all();

        $clientesData = $clientes->map(
            fn($model) => ClienteMapper::toDomain($model)->toArray()
        )->toArray();

        $productosData = $productos->map(
            fn($model) => ProductoMapper::toDomain($model)->toArray()
        )->toArray();

        return Inertia::render('Factura/create', [
            'clientes' => $clientesData,
            'productos' => $productosData,
        ]);
    }

    public function store(StoreFacturaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['usuario_id'] = auth()->id();

        $detalles = $validated['detalles'] ?? [];
        unset($validated['detalles']);

        DB::transaction(function () use ($validated, $detalles) {
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
        });

        return redirect()->route('facturas.index');
    }

    public function edit(string $id): Response
    {
        $factura = FacturaEloquentModel::with('detalles')->findOrFail($id);
        
        $facturaArray = FacturaMapper::toDomain($factura)->toArray();

        $facturaArray['detalles'] = $factura->detalles->map(function ($detalle) {
            return [
                'productoId' => $detalle->producto_id,
                'cantidad' => $detalle->cantidad,
                'precioUnitario' => (float) $detalle->precio_unitario,
                'descuento' => (float) $detalle->descuento,
                'subtotal' => (float) $detalle->subtotal,
            ];
        })->toArray();

        $clientes = ClienteEloquentModel::all();
        $productos = ProductoEloquentModel::all();

        $clientesData = $clientes->map(
            fn($model) => ClienteMapper::toDomain($model)->toArray()
        )->toArray();

        $productosData = $productos->map(
            fn($model) => ProductoMapper::toDomain($model)->toArray()
        )->toArray();

        return Inertia::render('Factura/edit', [
            'factura' => $facturaArray,
            'clientes' => $clientesData,
            'productos' => $productosData
        ]);
    }

    public function update(UpdateFacturaRequest $request, string $id): RedirectResponse
    {
        $validated = $request->validated();

        $factura = FacturaEloquentModel::findOrFail($id);
        $factura->update($validated);

        return redirect()->route('facturas.index');
    }

    public function destroy(string $id): RedirectResponse
    {
        $factura = FacturaEloquentModel::findOrFail($id);
        
        $factura->detalles()->delete();
        $factura->delete();

        return redirect()->route('facturas.index');
    }
}
