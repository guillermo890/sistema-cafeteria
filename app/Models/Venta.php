<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relation\belongsTo;
use Illuminate\Database\Relation\hasMany;

class Venta extends Model
{
    public function cliente(): BelongsTo
{
    return $this->belongsTo(Cliente::class, 'cliente_id');
}

public function usuario(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}

public function metodoPago(): BelongsTo
{
    return $this->belongsTo(MetodoPago::class, 'metodo_pago_id');
}

public function detalles(): HasMany
{
    return $this->hasMany(DetalleVenta::class, 'venta_id');
}

}
