<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        // Limpiar espacios y normalizar el correo
        $request->merge([
            'name' => trim($request->name ?? ''),
            'email' => strtolower(trim($request->email ?? ''))
        ]);

        // Validaciones de Laravel
        $datos = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\'-]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'confirmed'
            ],
            'rol_id' => [
                'required',
                'integer',
                'exists:roles,id'
            ]
        ], [
            'name.required' => 'El nombre del usuario es obligatorio.',
            'name.string' => 'El nombre debe ser texto.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 100 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, acentos, espacios, apóstrofes y guiones.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede superar los 64 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'rol_id.required' => 'Debes seleccionar un rol.',
            'rol_id.integer' => 'El rol seleccionado no es válido.',
            'rol_id.exists' => 'El rol seleccionado no existe.'
        ]);

        User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => Hash::make($datos['password']),
            'rol_id' => $datos['rol_id']
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
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

        // Limpiar espacios y normalizar el correo
        $request->merge([
            'name' => trim($request->name ?? ''),
            'email' => strtolower(trim($request->email ?? ''))
        ]);

        $datos = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\'-]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id)
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:64',
                'confirmed'
            ],
            'rol_id' => [
                'required',
                'integer',
                'exists:roles,id'
            ]
        ], [
            'name.required' => 'El nombre del usuario es obligatorio.',
            'name.string' => 'El nombre debe ser texto.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 100 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, acentos, espacios, apóstrofes y guiones.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no puede superar los 64 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',

            'rol_id.required' => 'Debes seleccionar un rol.',
            'rol_id.integer' => 'El rol seleccionado no es válido.',
            'rol_id.exists' => 'El rol seleccionado no existe.'
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->rol_id = $datos['rol_id'];

        // Solo cambiar la contraseña si se escribió una nueva
        if (!empty($datos['password'])) {
            $usuario->password = Hash::make($datos['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // Evitar eliminar usuarios que tengan ventas o compras
        if ($usuario->ventas()->exists() || $usuario->compras()->exists()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'No puedes eliminar este usuario porque tiene ventas o compras registradas.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
