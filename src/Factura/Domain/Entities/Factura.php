<?php

namespace Src\Factura\Domain\Entities;
use DateTimeImmutable;  

class Factura
{
    private string $id;
    private string $numeroFactura;
    private string $serie;
    private string $clienteId;
    private string $usuarioId;
    private DateTimeImmutable $fechaEmision;
    private ?DateTimeImmutable $fechaVencimiento;
    private float $subtotal;
    private float $igv;
    private float $descuento;
    private float $total;
    private string $estado;
    private ?string $observaciones;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $id,
        string $numeroFactura,
        string $serie,
        string $clienteId,
        string $usuarioId,
        DateTimeImmutable $fechaEmision,
        ?DateTimeImmutable $fechaVencimiento,
        float $subtotal,
        float $igv,
        float $descuento,
        float $total,
        string $estado,
        ?string $observaciones,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->numeroFactura = $numeroFactura;
        $this->serie = $serie;
        $this->clienteId = $clienteId;
        $this->usuarioId = $usuarioId;
        $this->fechaEmision = $fechaEmision;
        $this->fechaVencimiento = $fechaVencimiento;
        $this->subtotal = $subtotal;
        $this->igv = $igv;
        $this->descuento = $descuento;
        $this->total = $total;
        $this->estado = $estado;
        $this->observaciones = $observaciones;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumeroFactura(): string
    {
        return $this->numeroFactura;
    }

    public function getSerie(): string
    {
        return $this->serie;
    }

    public function getClienteId(): string
    {
        return $this->clienteId;
    }

    public function getUsuarioId(): string
    {
        return $this->usuarioId;
    }

    public function getFechaEmision(): DateTimeImmutable
    {
        return $this->fechaEmision;
    }

    public function getFechaVencimiento(): ?DateTimeImmutable
    {
        return $this->fechaVencimiento;
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getIgv(): float
    {
        return $this->igv;
    }

    public function getDescuento(): float
    {
        return $this->descuento;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateNumeroFactura(string $numeroFactura): void
    {
        $this->numeroFactura = $numeroFactura;
    }

    public function updateSerie(string $serie): void
    {
        $this->serie = $serie;
    }

    public function updateClienteId(string $clienteId): void
    {
        $this->clienteId = $clienteId;
    }

    public function updateFechaEmision(DateTimeImmutable $fechaEmision): void
    {
        $this->fechaEmision = $fechaEmision;
    }

    public function updateFechaVencimiento(?DateTimeImmutable $fechaVencimiento): void
    {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    public function updateSubtotal(float $subtotal): void
    {
        $this->subtotal = $subtotal;
    }

    public function updateIgv(float $igv): void
    {
        $this->igv = $igv;
    }

    public function updateDescuento(float $descuento): void
    {
        $this->descuento = $descuento;
    }

    public function updateTotal(float $total): void
    {
        $this->total = $total;
    }

    public function updateEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function updateObservaciones(?string $observaciones): void
    {
        $this->observaciones = $observaciones;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'numeroFactura' => $this->numeroFactura,
            'serie' => $this->serie,
            'clienteId' => $this->clienteId,
            'usuarioId' => $this->usuarioId,
            'fechaEmision' => $this->fechaEmision->format('Y-m-d H:i:s'),
            'fechaVencimiento' => $this->fechaVencimiento?->format('Y-m-d H:i:s'),
            'subtotal' => $this->subtotal,
            'igv' => $this->igv,
            'descuento' => $this->descuento,
            'total' => $this->total,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
