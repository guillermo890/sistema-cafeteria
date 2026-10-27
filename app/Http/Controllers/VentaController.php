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
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'metodo_pago_id' => 'required|exists:metodos_pagos,id',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id|distinct',
            'productos.*.cantidad' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {

            $metodo = MetodoPago::find($request->metodo_pago_id);

            if (!$metodo || !$metodo->estado) {
                throw ValidationException::withMessages([
                    'metodo_pago_id' => 'El método de pago no está disponible.'
                ]);
            }

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => $request->user_id,
                'metodo_pago_id' => $request->metodo_pago_id,
                'fecha_venta' => now(),
                'estado' => 'completada',
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->productos as $item) {

                $producto = Producto::find($item['producto_id']);

                if (!$producto || !$producto->estado) {
                    throw ValidationException::withMessages([
                        'productos' => 'Uno de los productos no está disponible.'
                    ]);
                }

                $cantidad = (int) $item['cantidad'];

                if ($producto->stock < $cantidad) {
                    throw ValidationException::withMessages([
                        'productos' => 'No hay suficiente existencia de ' . $producto->nombre .
                            '. Stock disponible: ' . $producto->stock
                    ]);
                }

                $subtotal = $producto->precio * $cantidad;

                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal
                ]);

                $producto->decrement('stock', $cantidad);

                $total += $subtotal;
            }

            $venta->update([
                'total' => $total
            ]);
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
