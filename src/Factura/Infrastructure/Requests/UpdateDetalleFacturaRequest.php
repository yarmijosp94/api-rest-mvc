<?php

namespace Src\Factura\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetalleFacturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'factura_id' => 'sometimes|required|string|exists:facturas,id',
            'producto_id' => 'sometimes|required|string|exists:productos,id',
            'cantidad' => 'sometimes|required|integer|min:1',
            'precio_unitario' => 'sometimes|required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'subtotal' => 'sometimes|required|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'factura_id.exists' => 'La factura especificada no existe',
            'producto_id.exists' => 'El producto especificado no existe',
            'cantidad.integer' => 'La cantidad debe ser un número entero',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'precio_unitario.numeric' => 'El precio unitario debe ser un número',
            'precio_unitario.min' => 'El precio unitario debe ser mayor o igual a 0',
            'descuento.numeric' => 'El descuento debe ser un número',
            'descuento.min' => 'El descuento debe ser mayor o igual a 0',
            'subtotal.numeric' => 'El subtotal debe ser un número',
            'subtotal.min' => 'El subtotal debe ser mayor o igual a 0'
        ];
    }
}
