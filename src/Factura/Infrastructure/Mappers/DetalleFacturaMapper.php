<?php

namespace Src\Factura\Infrastructure\Mappers;

use Src\Factura\Domain\Entities\DetalleFactura;
use Src\Factura\Infrastructure\Models\DetalleFacturaEloquentModel;

class DetalleFacturaMapper
{
    public static function toDomain(DetalleFacturaEloquentModel $model): DetalleFactura
    {
        return new DetalleFactura(
            id: $model->id,
            facturaId: $model->factura_id,
            productoId: $model->producto_id,
            cantidad: (int) $model->cantidad,
            precioUnitario: (float) $model->precio_unitario,
            descuento: (float) $model->descuento,
            subtotal: (float) $model->subtotal,
            createdAt: new \DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: new \DateTimeImmutable($model->updated_at->toDateTimeString())
        );
    }

    public static function toEloquent(DetalleFactura $detalleFactura): array
    {
        return [
            'id' => $detalleFactura->getId(),
            'factura_id' => $detalleFactura->getFacturaId(),
            'producto_id' => $detalleFactura->getProductoId(),
            'cantidad' => $detalleFactura->getCantidad(),
            'precio_unitario' => $detalleFactura->getPrecioUnitario(),
            'descuento' => $detalleFactura->getDescuento(),
            'subtotal' => $detalleFactura->getSubtotal(),
        ];
    }
}
