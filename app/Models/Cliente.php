<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\HasMany;

class Cliente extends Model
{
       protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'direccion'
    ];
    public function ventas(): HasMany
{
    return $this->hasMany(Venta::class, 'cliente_id');
}
}
