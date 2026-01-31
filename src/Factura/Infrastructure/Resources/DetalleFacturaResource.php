<?php

namespace Src\Factura\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetalleFacturaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'facturaId' => $this->factura_id,
            'productoId' => $this->producto_id,
            'cantidad' => $this->cantidad,
            'precioUnitario' => $this->precio_unitario,
            'descuento' => $this->descuento,
            'subtotal' => $this->subtotal,
            'factura' => $this->whenLoaded('factura', function() {
                return [
                    'id' => $this->factura->id,
                    'numeroFactura' => $this->factura->numero_factura,
                ];
            }),
            'producto' => $this->whenLoaded('producto', function() {
                return [
                    'id' => $this->producto->id,
                    'codigo' => $this->producto->codigo,
                    'nombre' => $this->producto->nombre,
                ];
            }),
            'createdAt' => $this->created_at?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
