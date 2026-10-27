<?php

namespace App\Http\Controllers;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

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
        $request->validate([
            'nombre' => 'required|max:50|unique:metodos_pagos,nombre',
            'estado' => 'required|boolean'
        ]);

        MetodoPago::create($request->all());

        return redirect()->route('metodos.index')
            ->with('success', 'Método de pago registrado correctamente');
    }

    public function edit($id)
    {
        $metodo = MetodoPago::findOrFail($id);

        return view('metodos.edit', compact('metodo'));
    }

    public function update(Request $request, $id)
    {
        $metodo = MetodoPago::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:50|unique:metodos_pagos,nombre,' . $id,
            'estado' => 'required|boolean'
        ]);

        $metodo->update($request->all());

        return redirect()->route('metodos.index')
            ->with('success', 'Método de pago actualizado correctamente');
    }

    public function destroy($id)
    {
        $metodo = MetodoPago::findOrFail($id);

        $metodo->delete();

        return redirect()->route('metodos.index')
            ->with('success', 'Método de pago eliminado correctamente');
    }
}
