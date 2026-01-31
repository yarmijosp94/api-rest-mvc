<?php

namespace Src\Factura\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacturaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Convert camelCase to snake_case for validation
        $this->merge([
            'numero_factura' => $this->numeroFactura,
            'cliente_id' => $this->clienteId,
            'usuario_id' => $this->usuarioId,
            'fecha_emision' => $this->fechaEmision,
            'fecha_vencimiento' => $this->fechaVencimiento,
        ]);

        // Convert detalles array from camelCase to snake_case
        if ($this->has('detalles') && is_array($this->detalles)) {
            $detalles = collect($this->detalles)->map(function ($detalle) {
                return [
                    'producto_id' => $detalle['productoId'] ?? null,
                    'cantidad' => $detalle['cantidad'] ?? null,
                    'precio_unitario' => $detalle['precioUnitario'] ?? null,
                    'descuento' => $detalle['descuento'] ?? null,
                    'subtotal' => $detalle['subtotal'] ?? null,
                ];
            })->toArray();

            $this->merge(['detalles' => $detalles]);
        }
    }

    public function rules(): array
    {
        return [
            'numero_factura' => 'required|string|unique:facturas,numero_factura',
            'serie' => 'required|string',
            'cliente_id' => 'required|string|exists:clientes,id',
            'usuario_id' => 'nullable|string|exists:users,id',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:fecha_emision',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado' => 'nullable|string|in:emitida,pagada,anulada',
            'observaciones' => 'nullable|string',
            'detalles' => 'nullable|array|min:0',
            'detalles.*.producto_id' => 'required|string|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.descuento' => 'nullable|numeric|min:0',
            'detalles.*.subtotal' => 'required|numeric|min:0'
        ];
    }

    public function attributes(): array
    {
        return [
            'numero_factura' => 'número de factura',
            'serie' => 'serie',
            'cliente_id' => 'cliente',
            'usuario_id' => 'usuario',
            'fecha_emision' => 'fecha de emisión',
            'fecha_vencimiento' => 'fecha de vencimiento',
            'subtotal' => 'subtotal',
            'igv' => 'IGV',
            'descuento' => 'descuento',
            'total' => 'total',
            'estado' => 'estado',
            'observaciones' => 'observaciones',
            'detalles.*.producto_id' => 'producto',
            'detalles.*.cantidad' => 'cantidad',
            'detalles.*.precio_unitario' => 'precio unitario',
            'detalles.*.descuento' => 'descuento del detalle',
            'detalles.*.subtotal' => 'subtotal del detalle',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_factura.required' => 'El número de factura es obligatorio',
            'numero_factura.unique' => 'Este número de factura ya existe',
            'serie.required' => 'La serie es obligatoria',
            'cliente_id.required' => 'El cliente es obligatorio',
            'cliente_id.exists' => 'El cliente especificado no existe',
            'usuario_id.required' => 'El usuario es obligatorio',
            'usuario_id.exists' => 'El usuario especificado no existe',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria',
            'fecha_emision.date' => 'La fecha de emisión debe ser una fecha válida',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento debe ser posterior o igual a la fecha de emisión',
            'subtotal.required' => 'El subtotal es obligatorio',
            'igv.required' => 'El IGV es obligatorio',
            'total.required' => 'El total es obligatorio',
            'estado.in' => 'El estado debe ser emitida, pagada o anulada',
            'detalles.required' => 'Al menos un detalle es obligatorio',
            'detalles.*.producto_id.exists' => 'El producto especificado no existe',
            'detalles.*.cantidad.min' => 'La cantidad debe ser al menos 1'
        ];
    }
}
