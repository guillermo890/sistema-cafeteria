<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\ProveedorController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categorias', CategoriaController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

Route::resource('productos', ProductoController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

Route::resource('ingredientes', IngredienteController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);    

Route::resource('proveedores', ProveedorController::class)
    ->parameters(['proveedores' => 'proveedor'])
    ->only(['index', 'store', 'edit', 'update', 'destroy']);