<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\MetodoPagoController;

Route::get('/', function () {
    return view('welcome');
});

// RUTAS DE ROLES
Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
Route::get('/roles/crear', [RolController::class, 'create'])->name('roles.create');
Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
Route::get('/roles/{id}/editar', [RolController::class, 'edit'])->name('roles.edit');
Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');

// RUTAS DE USUARIOS
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

// RUTAS DE CLIENTES
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/crear', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

// RUTAS DE CATEGORIAS
Route::resource('categorias', CategoriaController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

// RUTAS DE PRODUCTOS
Route::resource('productos', ProductoController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

// RUTAS DE INGREDIENTES
Route::resource('ingredientes', IngredienteController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

// RUTAS DE PROVEEDORES
Route::resource('proveedores', ProveedorController::class)
    ->parameters(['proveedores' => 'proveedor'])
    ->only(['index', 'store', 'edit', 'update', 'destroy']);

// RUTAS DE COMPRAS
Route::resource('compras', CompraController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

Route::patch('/compras/{compra}/recibir', [CompraController::class, 'recibir'])
    ->name('compras.recibir');

// RUTAS DE METODOS DE PAGO
Route::get('/metodos', [MetodoPagoController::class, 'index'])->name('metodos.index');
Route::get('/metodos/crear', [MetodoPagoController::class, 'create'])->name('metodos.create');
Route::post('/metodos', [MetodoPagoController::class, 'store'])->name('metodos.store');
Route::get('/metodos/{id}/editar', [MetodoPagoController::class, 'edit'])->name('metodos.edit');
Route::put('/metodos/{id}', [MetodoPagoController::class, 'update'])->name('metodos.update');
Route::delete('/metodos/{id}', [MetodoPagoController::class, 'destroy'])->name('metodos.destroy');

//RUTYAS DE VENTAS 
use App\Http\Controllers\VentaController;
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::get('/ventas/crear', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show');