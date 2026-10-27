<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class RolController extends Controller
{
public function index()
{
    $roles = Role::all();

   return view ('roles.index', compact('roles'));
}
public function create () 
{
    return view('roles.create');
}
public function edit($id)
{
    $rol = Role::findOrFail($id);

    return view('roles.edit', compact('rol'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|max:50|unique:roles,nombre,' . $id,
        'descripcion' => 'nullable'
    ]);

    $rol = Role::findOrFail($id);

    $rol->update([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion
    ]);

    return redirect()->route('roles.index')
        ->with('success', 'Rol actualizado correctamente');
}
public function destroy($id)
{
    $rol = Role::findOrFail($id);

    $rol->delete();

    return redirect()->route('roles.index')
        ->with('success', 'Rol eliminado correctamente');
}
public function store(Request $request)
{
$request ->validate([
   'nombre' => 'required|max:50|unique:roles,nombre',
    'descripcion' => "nullable"
]);
Role::create([
    'nombre' => $request ->nombre,
    'descripcion' => $request ->descripcion
]);
return redirect()-> route ('roles.index')
->with('success', 'Rol registrado correctamente');

}
}
