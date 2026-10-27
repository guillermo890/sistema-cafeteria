<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;

class IngredienteController extends Controller
{
    public function index()
    {
        $ingredientes = Ingrediente::orderBy('id', 'desc')->get();

        return view('ingredientes.index', compact('ingredientes'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100','regex:/^[\p{L}\s]+$/u'
            ],
            'unidad_medida' => 'required|string|max:30',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
        ]);

        $ingrediente = new Ingrediente();
        $ingrediente->nombre = $datos['nombre'];
        $ingrediente->unidad_medida = $datos['unidad_medida'];
        $ingrediente->stock = $datos['stock'];
        $ingrediente->stock_minimo = $datos['stock_minimo'];
        $ingrediente->estado = true;
        $ingrediente->save();

        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente registrado correctamente.');
    }

    public function edit(Ingrediente $ingrediente)
    {
        return view('ingredientes.edit', compact('ingrediente'));
    }

    public function update(Request $request, Ingrediente $ingrediente)
    {
        $datos = $request->validate([
            'nombre' => [
                'required', 'string', 'max:100',
                'regex:/^[\p{L}\s]+$/u'
            ],
            'unidad_medida' => 'required|string|max:30',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
        ]);

        $ingrediente->nombre = $datos['nombre'];
        $ingrediente->unidad_medida = $datos['unidad_medida'];
        $ingrediente->stock = $datos['stock'];
        $ingrediente->stock_minimo = $datos['stock_minimo'];
        $ingrediente->save();

        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente actualizado correctamente.');
    }

    public function destroy(Ingrediente $ingrediente)
    {
        if (
            $ingrediente->productos()->exists() ||
            $ingrediente->detallesCompra()->exists()
        ) {
            return redirect()->route('ingredientes.index')
                ->with('error', 'No se puede eliminar un ingrediente asociado a productos o compras.');
        }

        $ingrediente->delete();

        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente eliminado correctamente.');
    }
}
