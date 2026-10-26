<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    public function ventas(): HasMany
{
    return $this->hasMany(Venta::class, 'metodo_pago_id');
}
}
