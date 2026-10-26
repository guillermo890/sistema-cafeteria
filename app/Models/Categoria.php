<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Relations\HasMany;


class Categoria extends Model
{
    public function productos(): HasMany
{
    return $this->hasMany(Producto::class, 'categoria_id');
}

}
