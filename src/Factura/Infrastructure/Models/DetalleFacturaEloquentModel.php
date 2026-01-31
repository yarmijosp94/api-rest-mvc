<?php

namespace Src\Factura\Infrastructure\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Producto\Infrastructure\Models\ProductoEloquentModel;

class DetalleFacturaEloquentModel extends Model
{
    use HasUuid;

    protected $table = 'detalle_facturas';

    protected $fillable = [
        'id',
        'factura_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'descuento' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(FacturaEloquentModel::class, 'factura_id', 'id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(ProductoEloquentModel::class, 'producto_id', 'id');
    }
}
