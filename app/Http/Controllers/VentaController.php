<?php
namespace App\Http\Controllers;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\User;
use App\Models\MetodoPago;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with([
            'cliente',
            'usuario',
            'metodoPago',
            'detalles.producto'
        ])->latest()->get();

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $usuarios = User::all();
        $metodos = MetodoPago::where('estado', true)->get();
        $productos = Producto::where('estado', true)
            ->where('stock', '>', 0)
            ->get();
        return view('ventas.create', compact(
            'clientes',
            'usuarios',
            'metodos',
            'productos'
        ));
    }

    public function store(Request $request)
    {
        // Validaciones principales de Laravel
        $datos = $request->validate([
            'cliente_id' => [
                'nullable',
                'integer',
                'exists:clientes,id'
            ],

            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],

            'metodo_pago_id' => [
                'required',
                'integer',
                'exists:metodos_pagos,id'
            ],

            'productos' => [
                'required',
                'array',
                'min:1',
                'max:50'
            ],

            'productos.*' => [
                'required',
                'array:producto_id,cantidad'
            ],

            'productos.*.producto_id' => [
                'required',
                'integer',
                'distinct:strict',
                'exists:productos,id'
            ],

            'productos.*.cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:9999'
            ]

        ], [
            'cliente_id.integer' => 'El cliente seleccionado no es válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',

            'user_id.required' => 'Debes seleccionar un usuario.',
            'user_id.integer' => 'El usuario seleccionado no es válido.',
            'user_id.exists' => 'El usuario seleccionado no existe.',

            'metodo_pago_id.required' => 'Debes seleccionar un método de pago.',
            'metodo_pago_id.integer' => 'El método de pago no es válido.',
            'metodo_pago_id.exists' => 'El método de pago seleccionado no existe.',

            'productos.required' => 'Debes agregar productos a la venta.',
            'productos.array' => 'Los productos enviados no son válidos.',
            'productos.min' => 'La venta debe contener al menos un producto.',
            'productos.max' => 'No puedes agregar más de 50 productos diferentes.',

            'productos.*.producto_id.required' => 'Debes seleccionar un producto.',
            'productos.*.producto_id.integer' => 'El producto seleccionado no es válido.',
            'productos.*.producto_id.distinct' => 'No puedes agregar el mismo producto más de una vez.',
            'productos.*.producto_id.exists' => 'Uno de los productos seleccionados no existe.',

            'productos.*.cantidad.required' => 'Debes indicar la cantidad.',
            'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'productos.*.cantidad.min' => 'La cantidad mínima es 1.',
            'productos.*.cantidad.max' => 'La cantidad no puede superar 9999 unidades.'
        ]);

        DB::transaction(function () use ($datos) {

            // Comprobar que el usuario exista
            $usuario = User::find($datos['user_id']);

            if (!$usuario) {
                throw ValidationException::withMessages([
                    'user_id' => 'El usuario seleccionado ya no existe.'
                ]);
            }

            // Comprobar que el cliente exista si se seleccionó uno
            if (!empty($datos['cliente_id'])) {
                $cliente = Cliente::find($datos['cliente_id']);

                if (!$cliente) {
                    throw ValidationException::withMessages([
                        'cliente_id' => 'El cliente seleccionado ya no existe.'
                    ]);
                }
            }

            // Comprobar que el método de pago esté activo
            $metodo = MetodoPago::where('id', $datos['metodo_pago_id'])
                ->lockForUpdate()
                ->first();

            if (!$metodo || !$metodo->estado) {
                throw ValidationException::withMessages([
                    'metodo_pago_id' => 'El método de pago no está disponible.'
                ]);
            }

            $productosVenta = [];
            $totalCentavos = 0;

            foreach ($datos['productos'] as $item) {

                // Bloquear el producto durante la operación
                $producto = Producto::where('id', $item['producto_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$producto || !$producto->estado) {
                    throw ValidationException::withMessages([
                        'productos' => 'Uno de los productos no está disponible.'
                    ]);
                }

                $cantidad = (int) $item['cantidad'];

                // Comprobar que exista suficiente stock
                if ($cantidad > $producto->stock) {
                    throw ValidationException::withMessages([
                        'productos' => 'No hay suficiente existencia de '
                            . $producto->nombre
                            . '. Stock disponible: '
                            . $producto->stock
                    ]);
                }

                // Comprobar que el precio sea válido
                if (!is_numeric($producto->precio) || $producto->precio <= 0) {
                    throw ValidationException::withMessages([
                        'productos' => 'El producto '
                            . $producto->nombre
                            . ' tiene un precio inválido.'
                    ]);
                }

                // Trabajar con centavos para evitar errores de decimales
                $precioCentavos = (int) round(
                    (float) $producto->precio * 100
                );

                $subtotalCentavos = $precioCentavos * $cantidad;

                $totalCentavos += $subtotalCentavos;

                // Comprobar el límite de la columna decimal(10,2)
                if ($totalCentavos > 9999999999) {
                    throw ValidationException::withMessages([
                        'productos' => 'El total de la venta supera el límite permitido.'
                    ]);
                }

                $productosVenta[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio' => number_format($precioCentavos / 100, 2, '.', ''),
                    'subtotal' => number_format($subtotalCentavos / 100, 2, '.', '')
                ];
            }

            // Crear la venta solamente después de comprobar los productos
            $venta = Venta::create([
                'cliente_id' => $datos['cliente_id'] ?? null,
                'user_id' => $datos['user_id'],
                'metodo_pago_id' => $datos['metodo_pago_id'],
                'fecha_venta' => now(),
                'estado' => 'completada',
                'total' => number_format($totalCentavos / 100, 2, '.', '')
            ]);

            // Registrar los detalles y descontar el inventario
            foreach ($productosVenta as $item) {

                $venta->detalles()->create([
                    'producto_id' => $item['producto']->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['subtotal']
                ]);

                $item['producto']->decrement(
                    'stock',
                    $item['cantidad']
                );
            }
        });

        return redirect()->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');
    }

    public function show($id)
    {
        $venta = Venta::with([
            'cliente',
            'usuario',
            'metodoPago',
            'detalles.producto'
        ])->findOrFail($id);

        return view('ventas.show', compact('venta'));
    }
}
