<?php

namespace Src\Producto\Infrastructure\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Categoria\Infrastructure\Models\CategoriaEloquentModel;
use Src\Factura\Infrastructure\Models\DetalleFacturaEloquentModel;

class ProductoEloquentModel extends Model
{
    use HasUuid;

    protected $table = 'productos';

    protected $fillable = [
        'id',
        'categoria_id',
        'codigo',
        'nombre',
        'descripcion',
        'precio_unitario',
        'stock',
        'tipo',
        'activo'
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'stock' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaEloquentModel::class);
    }

    public function detalleFacturas(): HasMany
    {
        return $this->hasMany(DetalleFacturaEloquentModel::class, 'producto_id', 'id');
    }
}
