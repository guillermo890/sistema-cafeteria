<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('id', 'desc')->get();

        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
            'descripcion' => 'nullable|string',
        ]);

        $categoria = new Categoria();
        $categoria->nombre = $datos['nombre'];
        $categoria->descripcion = $datos['descripcion'] ?? null;
        $categoria->estado = true;
        $categoria->save();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
    $datos = $request->validate([
        'nombre' => ['required', 'string', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
        'descripcion' => 'nullable|string',
    ], [
        'nombre.regex' => 'El nombre de la categoría solo puede contener letras y espacios.',
        'nombre.required' => 'El nombre de la categoría es obligatorio.',
    ]);

    $categoria->nombre = $datos['nombre'];
    $categoria->descripcion = $datos['descripcion'] ?? null;
    $categoria->save();

    return redirect()->route('categorias.index')
        ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar una categoría con productos.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
