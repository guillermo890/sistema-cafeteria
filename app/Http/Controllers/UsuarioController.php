<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('rol')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'rol_id' => 'required|exists:roles,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'rol_id' => 'required|exists:roles,id'
        ]);

        $datos = [
            'name' => $request->name,
            'email' => $request->email,
            'rol_id' => $request->rol_id
        ];

        if ($request->password) {
            $request->validate([
                'password' => 'min:8'
            ]);

            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
