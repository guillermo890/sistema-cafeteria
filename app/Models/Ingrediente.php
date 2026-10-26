<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingrediente extends Model
{
    public function productos(): BelongsToMany
{
    return $this->belongsToMany(
        Producto::class,
        'producto_ingrediente',
        'ingrediente_id',
        'producto_id'
    )->withPivot('cantidad')->withTimestamps();
}

public function detallesCompra(): HasMany
{
    return $this->hasMany(DetalleCompra::class, 'ingrediente_id');
}

}
