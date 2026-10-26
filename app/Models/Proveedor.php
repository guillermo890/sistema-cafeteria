<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\hasMany;

class Proveedor extends Model
{
    public function compras(): HasMany
{
    return $this->hasMany(Compra::class, 'proveedor_id');
}
}
