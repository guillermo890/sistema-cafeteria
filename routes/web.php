<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/roles', [RolController::class, 'index']) ->name('roles.index');
Route::get ('roles/crear', [RolController::class, 'create']) ->name('roles.create');
Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
Route::get('/roles/{id}/editar', [RolController::class, 'edit'])->name('roles.edit');
Route::put('/roles/{id}', [RolController::class, 'update'])->name('roles.update');
Route::delete('/roles/{id}', [RolController::class, 'destroy'])->name('roles.destroy');