<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('id', 'desc')->get();

        return view('proveedores.index', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\s]+$/u',
            ],
            'telefono' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono debe contener exactamente 10 dígitos.',
            'email.email' => 'Introduce un correo electrónico válido.',
        ]);

        $proveedor = new Proveedor();
        $proveedor->nombre = $datos['nombre'];
        $proveedor->telefono = $datos['telefono'] ?? null;
        $proveedor->email = $datos['email'] ?? null;
        $proveedor->direccion = $datos['direccion'] ?? null;
        $proveedor->estado = true;
        $proveedor->save();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', [
            'proveedor' => $proveedor,
        ]);
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\s]+$/u',
            ],
            'telefono' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'telefono.regex' => 'El teléfono debe contener exactamente 10 dígitos.',
            'email.email' => 'Introduce un correo electrónico válido.',
        ]);

        $proveedor->nombre = $datos['nombre'];
        $proveedor->telefono = $datos['telefono'] ?? null;
        $proveedor->email = $datos['email'] ?? null;
        $proveedor->direccion = $datos['direccion'] ?? null;
        $proveedor->save();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->compras()->exists()) {
            return redirect()->route('proveedores.index')
                ->with('error', 'No se puede eliminar un proveedor que tiene compras registradas.');
        }

        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}