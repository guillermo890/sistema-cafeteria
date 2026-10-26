<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    public function compra(): BelongsTo
{
    return $this->belongsTo(Compra::class, 'compra_id');
}

public function ingrediente(): BelongsTo
{
    return $this->belongsTo(Ingrediente::class, 'ingrediente_id');
}

}
