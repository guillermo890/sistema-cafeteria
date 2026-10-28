<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')
            ->orderBy('id', 'desc')
            ->get();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
        'categoria_id' => ['required', 'exists:categorias,id'],
        'nombre' => [
            'required',
            'string',
            'max:100',
            'regex:/^[\p{L}\s]+$/u',
            Rule::unique('productos', 'nombre'),
        ],
        'descripcion' => ['nullable', 'string'],
        'precio' => ['required', 'numeric', 'min:0.01'],
        'stock' => ['required', 'integer', 'min:0'],
    ], [
        'categoria_id.required' => 'Debes seleccionar una categoría.',
        'categoria_id.exists' => 'La categoría seleccionada no existe.',
        'nombre.required' => 'El nombre del producto es obligatorio.',
        'nombre.string' => 'El nombre del producto debe ser texto.',
        'nombre.max' => 'El nombre del producto no puede superar los 100 caracteres.',
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'nombre.unique' => 'Ya existe un producto con ese nombre.',
        'precio.required' => 'El precio es obligatorio.',
        'precio.numeric' => 'El precio debe ser un número válido.',
        'precio.min' => 'El precio debe ser mayor que cero.',
        'stock.required' => 'El stock es obligatorio.',
        'stock.integer' => 'El stock debe ser un número entero.',
        'stock.min' => 'El stock no puede ser negativo.',
    ]);

        $producto = new Producto();
        $producto->categoria_id = $datos['categoria_id'];
        $producto->nombre = $datos['nombre'];
        $producto->descripcion = $datos['descripcion'] ?? null;
        $producto->precio = $datos['precio'];
        $producto->stock = $datos['stock'];
        $producto->estado = true;
        $producto->save();

        return redirect()->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
        'categoria_id' => ['required', 'exists:categorias,id'],
        'nombre' => [
            'required',
            'string',
            'max:100',
            'regex:/^[\p{L}\s]+$/u',
            Rule::unique('productos', 'nombre')->ignore($producto->id),
        ],
        'descripcion' => ['nullable', 'string'],
        'precio' => ['required', 'numeric', 'min:0.01'],
        'stock' => ['required', 'integer', 'min:0'],
    ], [
        'categoria_id.required' => 'Debes seleccionar una categoría.',
        'categoria_id.exists' => 'La categoría seleccionada no existe.',
        'nombre.required' => 'El nombre del producto es obligatorio.',
        'nombre.string' => 'El nombre del producto debe ser texto.',
        'nombre.max' => 'El nombre del producto no puede superar los 100 caracteres.',
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'nombre.unique' => 'Ya existe un producto con ese nombre.',
        'precio.required' => 'El precio es obligatorio.',
        'precio.numeric' => 'El precio debe ser un número válido.',
        'precio.min' => 'El precio debe ser mayor que cero.',
        'stock.required' => 'El stock es obligatorio.',
        'stock.integer' => 'El stock debe ser un número entero.',
        'stock.min' => 'El stock no puede ser negativo.',
    ]);

        $producto->categoria_id = $datos['categoria_id'];
        $producto->nombre = $datos['nombre'];
        $producto->descripcion = $datos['descripcion'] ?? null;
        $producto->precio = $datos['precio'];
        $producto->stock = $datos['stock'];
        $producto->save();

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->detallesVenta()->exists()) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar un producto que tiene ventas.');
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}