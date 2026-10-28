<?php
namespace App\Http\Controllers;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        // Limpiar espacios y normalizar correo
        $request->merge([
            'nombre' => trim($request->nombre ?? ''),
            'email' => $request->filled('email')
                ? strtolower(trim($request->email))
                : null,
            'telefono' => $request->filled('telefono')
                ? trim($request->telefono)
                : null,
            'direccion' => $request->filled('direccion')
                ? trim($request->direccion)
                : null
        ]);

        // Validaciones de Laravel
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\'-]+$/u'
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('clientes', 'email')
            ],

            'telefono' => [
                'nullable',
                'regex:/^[0-9]{10}$/'
            ],

            'direccion' => [
                'nullable',
                'string',
                'min:5',
                'max:255'
            ]
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, acentos, espacios, apóstrofes y guiones.',

            'email.string' => 'El correo debe ser texto.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',

            'telefono.regex' => 'El teléfono debe contener exactamente 10 números, sin espacios ni guiones.',

            'direccion.string' => 'La dirección debe ser texto.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.'
        ]);

        Cliente::create($datos);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        // Limpiar espacios y normalizar correo
        $request->merge([
            'nombre' => trim($request->nombre ?? ''),
            'email' => $request->filled('email')
                ? strtolower(trim($request->email))
                : null,
            'telefono' => $request->filled('telefono')
                ? trim($request->telefono)
                : null,
            'direccion' => $request->filled('direccion')
                ? trim($request->direccion)
                : null
        ]);

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\'-]+$/u'
            ],

            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('clientes', 'email')->ignore($cliente->id)
            ],

            'telefono' => [
                'nullable',
                'regex:/^[0-9]{10}$/'
            ],

            'direccion' => [
                'nullable',
                'string',
                'min:5',
                'max:255'
            ]
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, acentos, espacios, apóstrofes y guiones.',

            'email.string' => 'El correo debe ser texto.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',

            'telefono.regex' => 'El teléfono debe contener exactamente 10 números, sin espacios ni guiones.',

            'direccion.string' => 'La dirección debe ser texto.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.'
        ]);

        $cliente->update($datos);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        // Comprobar si el cliente tiene ventas
        if ($cliente->ventas()->exists()) {
            return redirect()->route('clientes.index')
                ->with('error', 'No puedes eliminar este cliente porque tiene ventas registradas.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
