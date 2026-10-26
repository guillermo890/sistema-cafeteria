<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('categorias', CategoriaController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

Route::resource('productos', ProductoController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);