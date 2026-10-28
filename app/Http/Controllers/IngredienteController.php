<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ingrediente;
use Illuminate\Validation\Rule;
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
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\s]+$/u',
                Rule::unique('ingredientes', 'nombre'),
            ],
            'unidad_medida' => ['required', 'string', 'max:30'],
            'precio' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => [
                'required',
                'numeric',
                'min:0',
                'lte:stock',
            ],
        ], [
            'nombre.required' => 'El nombre del ingrediente es obligatorio.',
            'nombre.string' => 'El nombre del ingrediente debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.unique' => 'Ya existe un ingrediente con ese nombre.',

            'unidad_medida.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida.max' => 'La unidad de medida no puede superar los 30 caracteres.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.gt' => 'El precio debe ser mayor que cero.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.numeric' => 'El stock debe ser un número válido.',
            'stock.min' => 'El stock no puede ser negativo.',

            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.numeric' => 'El stock mínimo debe ser un número válido.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
            'stock_minimo.lte' => 'El stock mínimo no puede ser mayor que el stock disponible.',
        ]);

        $ingrediente = new Ingrediente();
        $ingrediente->nombre = $datos['nombre'];
        $ingrediente->unidad_medida = $datos['unidad_medida'];
        $ingrediente->precio = $datos['precio'];
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
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\s]+$/u',
                Rule::unique('ingredientes', 'nombre')->ignore($ingrediente->id),
            ],
            'unidad_medida' => ['required', 'string', 'max:30'],
            'precio' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'numeric', 'min:0'],
            'stock_minimo' => [
                'required',
                'numeric',
                'min:0',
                'lte:stock',
            ],
        ], [
            'nombre.required' => 'El nombre del ingrediente es obligatorio.',
            'nombre.string' => 'El nombre del ingrediente debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'nombre.unique' => 'Ya existe un ingrediente con ese nombre.',

            'unidad_medida.required' => 'La unidad de medida es obligatoria.',
            'unidad_medida.max' => 'La unidad de medida no puede superar los 30 caracteres.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.gt' => 'El precio debe ser mayor que cero.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.numeric' => 'El stock debe ser un número válido.',
            'stock.min' => 'El stock no puede ser negativo.',

            'stock_minimo.required' => 'El stock mínimo es obligatorio.',
            'stock_minimo.numeric' => 'El stock mínimo debe ser un número válido.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
            'stock_minimo.lte' => 'El stock mínimo no puede ser mayor que el stock disponible.',
        ]);

        $ingrediente->nombre = $datos['nombre'];
        $ingrediente->unidad_medida = $datos['unidad_medida'];
        $ingrediente->precio = $datos['precio'];
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
