<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    public function categoria(): BelongsTo
{
    return $this->belongsTo(Categoria::class, 'categoria_id');
}

public function ingredientes(): BelongsToMany
{
    return $this->belongsToMany(
        Ingrediente::class,
        'producto_ingrediente',
        'producto_id',
        'ingrediente_id'
    )->withPivot('cantidad')->withTimestamps();
}

public function detallesVenta(): HasMany
{
    return $this->hasMany(DetalleVenta::class, 'producto_id');
}

}
