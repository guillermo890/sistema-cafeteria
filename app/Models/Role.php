<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
    'nombre',
    'descripcion'    
    ];
    
    public function usuarios(): HasMany
{
    return $this->hasMany(User::class, 'rol_id');
}

}
