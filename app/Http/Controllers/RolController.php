<?php
namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class RolController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('usuarios')->get();

        return view('roles.index', compact('roles'));
    }
    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'nombre' => trim($request->nombre ?? ''),
            'descripcion' => $request->filled('descripcion')
                ? trim($request->descripcion)
                : null
        ]);

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[\pL\s-]+$/u',
                Rule::unique('roles', 'nombre')
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255'
            ]
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y guiones bajos.',
            'nombre.unique' => 'Este nombre de rol ya está registrado.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
        ]);

        Role::create($datos);

        return redirect()->route('roles.index')
            ->with('success', 'Rol registrado correctamente.');
    }

    public function edit($id)
    {
        $rol = Role::findOrFail($id);

        return view('roles.edit', compact('rol'));
    }

    public function update(Request $request, $id)
    {
        $rol = Role::findOrFail($id);

        $request->merge([
            'nombre' => trim($request->nombre ?? ''),
            'descripcion' => $request->filled('descripcion')
                ? trim($request->descripcion)
                : null
        ]);

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[\pL\pN\s_-]+$/u',
                Rule::unique('roles', 'nombre')->ignore($rol->id)
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255'
            ]
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y guiones bajos.',
            'nombre.unique' => 'Este nombre de rol ya está registrado.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.'
        ]);

        $rol->update($datos);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy($id)
    {
        $rol = Role::findOrFail($id);

        if ($rol->usuarios()->exists()) {
            return redirect()->route('roles.index')
                ->with('error', 'No puedes eliminar este rol porque tiene usuarios asignados.');
        }

        $rol->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}
