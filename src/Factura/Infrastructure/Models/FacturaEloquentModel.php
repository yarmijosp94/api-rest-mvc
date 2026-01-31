<?php

namespace Src\Factura\Infrastructure\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Src\Cliente\Infrastructure\Models\ClienteEloquentModel;
use Src\Factura\Infrastructure\Models\DetalleFacturaEloquentModel;
use Src\Auth\Infrastructure\Models\UserEloquentModel; 
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacturaEloquentModel extends Model
{
    use HasUuid;

    protected $table = 'facturas';

    protected $fillable = [
        'id',
        'numero_factura',
        'serie',
        'cliente_id',
        'usuario_id',
        'fecha_emision',
        'fecha_vencimiento',
        'subtotal',
        'igv',
        'descuento',
        'total',
        'estado',
        'observaciones'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'subtotal' => 'decimal:2',
        'igv' => 'decimal:2',
        'descuento' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(ClienteEloquentModel::class, 'cliente_id', 'id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleFacturaEloquentModel::class, 'factura_id', 'id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(UserEloquentModel::class, 'usuario_id', 'id');
    }
}
