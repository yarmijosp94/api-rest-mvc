<?php

namespace Src\Factura\Domain\Entities;
use DateTimeImmutable;

class DetalleFactura
{
    private string $id;
    private string $facturaId;
    private string $productoId;
    private int $cantidad;
    private float $precioUnitario;
    private float $descuento;
    private float $subtotal;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $id,
        string $facturaId,
        string $productoId,
        int $cantidad,
        float $precioUnitario,
        float $descuento,
        float $subtotal,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->facturaId = $facturaId;
        $this->productoId = $productoId;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->descuento = $descuento;
        $this->subtotal = $subtotal;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFacturaId(): string
    {
        return $this->facturaId;
    }

    public function getProductoId(): string
    {
        return $this->productoId;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function getPrecioUnitario(): float
    {
        return $this->precioUnitario;
    }

    public function getDescuento(): float
    {
        return $this->descuento;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function updatePrecioUnitario(float $precioUnitario): void
    {
        $this->precioUnitario = $precioUnitario;
    }

    public function updateDescuento(float $descuento): void
    {
        $this->descuento = $descuento;
    }

    public function updateSubtotal(float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'factura_id' => $this->facturaId,
            'producto_id' => $this->productoId,
            'cantidad' => $this->cantidad,
            'precio_unitario' => $this->precioUnitario,
            'descuento' => $this->descuento,
            'subtotal' => $this->subtotal,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
