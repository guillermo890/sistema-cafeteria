<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model

{
    public function venta(): BelongsTo
{
    return $this->belongsTo(Venta::class, 'venta_id');
}

public function producto(): BelongsTo
{
    return $this->belongsTo(Producto::class, 'producto_id');
}

}
