<?php

namespace Src\Cliente\Application\Controllers;

use App\Http\Controllers\Controller;
use Src\Cliente\Infrastructure\Requests\StoreClienteRequest;
use Src\Cliente\Infrastructure\Requests\UpdateClienteRequest;
use Src\Cliente\Infrastructure\Resources\ClienteResource;
use Src\Cliente\Infrastructure\Models\ClienteEloquentModel;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = ClienteEloquentModel::all();
        return ClienteResource::collection($clientes);
    }

    public function store(StoreClienteRequest $request)
    {
        $cliente = ClienteEloquentModel::create($request->validated());
        return new ClienteResource($cliente);
    }

    public function show(string $id)
    {
        $cliente = ClienteEloquentModel::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        return new ClienteResource($cliente);
    }

    public function update(UpdateClienteRequest $request, string $id)
    {
        $cliente = ClienteEloquentModel::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        $cliente->update($request->validated());
        return new ClienteResource($cliente);
    }

    public function destroy(string $id)
    {
        $cliente = ClienteEloquentModel::find($id);

        if (!$cliente) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado'
            ], 404);
        }

        if ($cliente->facturas()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar este cliente porque tiene facturas asociadas'
            ], 400);
        }

        $cliente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado exitosamente'
        ], 200);
    }
}
