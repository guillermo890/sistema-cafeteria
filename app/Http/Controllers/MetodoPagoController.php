<?php
namespace App\Http\Controllers;
use App\Models\MetodoPago;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class MetodoPagoController extends Controller
{
    public function index()
    {
        $metodos = MetodoPago::all();

        return view('metodos.index', compact('metodos'));
    }

    public function create()
    {
        return view('metodos.create');
    }

    public function store(Request $request)
    {
        // Limpiar espacios del nombre
        $request->merge([
            'nombre' => trim($request->nombre ?? '')
        ]);

        // Validaciones de Laravel
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[\pL\s\'-]+$/u',
                Rule::unique('metodos_pagos', 'nombre')
            ],
            'estado' => [
                'required',
                'boolean'
            ]
        ], [
            'nombre.required' => 'El nombre del método de pago es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, acentos, espacios, apóstrofes y guiones.',
            'nombre.unique' => 'Este método de pago ya está registrado.',

            'estado.required' => 'Debes seleccionar el estado.',
            'estado.boolean' => 'El estado seleccionado no es válido.'
        ]);

        MetodoPago::create($datos);

        return redirect()->route('metodos.index')
            ->with('success', 'Método de pago registrado correctamente.');
    }

    public function edit($id)
    {
        $metodo = MetodoPago::findOrFail($id);

        return view('metodos.edit', compact('metodo'));
    }

    public function update(Request $request, $id)
    {
        $metodo = MetodoPago::findOrFail($id);

        $request->merge([
            'nombre' => trim($request->nombre ?? '')
        ]);

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[\pL\s\'-]+$/u',
                Rule::unique('metodos_pagos', 'nombre')->ignore($metodo->id)
            ],
            'estado' => [
                'required',
                'boolean'
            ]
        ], [
            'nombre.required' => 'El nombre del método de pago es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, acentos, espacios, apóstrofes y guiones.',
            'nombre.unique' => 'Este método de pago ya está registrado.',

            'estado.required' => 'Debes seleccionar el estado.',
            'estado.boolean' => 'El estado seleccionado no es válido.'
        ]);

        $metodo->update($datos);

        return redirect()->route('metodos.index')
            ->with('success', 'Método de pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        $metodo = MetodoPago::findOrFail($id);

        // Comprobar si el método ya se utilizó en alguna venta
        if (Venta::where('metodo_pago_id', $metodo->id)->exists()) {
            return redirect()->route('metodos.index')
                ->with('error', 'No puedes eliminar este método porque está asociado a ventas registradas.');
        }

        $metodo->delete();

        return redirect()->route('metodos.index')
            ->with('success', 'Método de pago eliminado correctamente.');
    }
}

