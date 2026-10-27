<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use Illuminate\Http\Request;

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
        $request->validate([
            'nombre' => 'required|max:100',
            'email' => 'nullable|email|max:255|unique:clientes,email',
            'telefono' => 'nullable|max:20',
            'direccion' => 'nullable|max:255'
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:100',
            'email' => 'nullable|email|max:255|unique:clientes,email,' . $id,
            'telefono' => 'nullable|max:20',
            'direccion' => 'nullable|max:255'
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente');
    }
}
