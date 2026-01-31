<?php

namespace Src\Factura\Infrastructure\Mappers;

use Src\Factura\Domain\Entities\Factura;
use Src\Factura\Infrastructure\Models\FacturaEloquentModel;
use DateTimeImmutable;

class FacturaMapper
{
    public static function toDomain(FacturaEloquentModel $model): Factura
    {
        return new Factura(
            id: $model->id,
            numeroFactura: $model->numero_factura,
            serie: $model->serie,
            clienteId: $model->cliente_id,
            usuarioId: $model->usuario_id,
            fechaEmision: new DateTimeImmutable($model->fecha_emision->toDateTimeString()),
            fechaVencimiento: $model->fecha_vencimiento ? new DateTimeImmutable($model->fecha_vencimiento->toDateTimeString()) : null,
            subtotal: (float) $model->subtotal,
            igv: (float) $model->igv,
            descuento: (float) $model->descuento,
            total: (float) $model->total,
            estado: $model->estado,
            observaciones: $model->observaciones,
            createdAt: new DateTimeImmutable($model->created_at->toDateTimeString()),
            updatedAt: new DateTimeImmutable($model->updated_at->toDateTimeString())
        );
    }

    public static function toEloquent(Factura $factura): array
    {
        return [
            'id' => $factura->getId(),
            'numero_factura' => $factura->getNumeroFactura(),
            'serie' => $factura->getSerie(),
            'cliente_id' => $factura->getClienteId(),
            'usuario_id' => $factura->getUsuarioId(),
            'fecha_emision' => $factura->getFechaEmision()->format('Y-m-d H:i:s'),
            'fecha_vencimiento' => $factura->getFechaVencimiento()?->format('Y-m-d H:i:s'),
            'subtotal' => $factura->getSubtotal(),
            'igv' => $factura->getIgv(),
            'descuento' => $factura->getDescuento(),
            'total' => $factura->getTotal(),
            'estado' => $factura->getEstado(),
            'observaciones' => $factura->getObservaciones(),
        ];
    }
}
