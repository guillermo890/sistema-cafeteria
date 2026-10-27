<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Ingrediente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['proveedor', 'usuario', 'detalles.ingrediente'])
            ->orderByDesc('id')
            ->get();

        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('compras.create', compact(
            'proveedores', 'ingredientes', 'usuarios'
        ));
    }

    private function validarCompra(Request $request): array
    {
        return $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'user_id' => 'required|exists:users,id',
            'fecha_compra' => 'required|date',
            'observaciones' => 'nullable|string|max:1000',
            'detalles' => 'required|array|min:1',
            'detalles.*.ingrediente_id' => [
                'required',
                'distinct',
                'exists:ingredientes,id',
            ],
            'detalles.*.cantidad' => 'required|numeric|gt:0',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);
    }

    private function guardarDetalles(Compra $compra, array $detalles): float
    {
        $total = 0;

        foreach ($detalles as $detalle) {
            $subtotal = round(
                $detalle['cantidad'] * $detalle['precio_unitario'],
                2
            );

            $registro = new DetalleCompra();
            $registro->compra_id = $compra->id;
            $registro->ingrediente_id = $detalle['ingrediente_id'];
            $registro->cantidad = $detalle['cantidad'];
            $registro->precio_unitario = $detalle['precio_unitario'];
            $registro->subtotal = $subtotal;
            $registro->save();

            $total += $subtotal;
        }

        return round($total, 2);
    }

    public function store(Request $request)
    {

    $request->merge([

    'detalles' => array_values(array_filter(
        $request->input('detalles', []),
        fn ($detalle) => !empty($detalle['ingrediente_id'])
        )),
    ]);

        $datos = $this->validarCompra($request);

        DB::transaction(function () use ($datos) {
            $compra = new Compra();
            $compra->proveedor_id = $datos['proveedor_id'];
            $compra->user_id = $datos['user_id'];
            $compra->fecha_compra = $datos['fecha_compra'];
            $compra->observaciones = $datos['observaciones'] ?? null;
            $compra->estado = 'pendiente';
            $compra->total = 0;
            $compra->save();

            $compra->total = $this->guardarDetalles(
                $compra,
                $datos['detalles']
            );

            $compra->save();
        });

        return redirect()->route('compras.index')
            ->with('success', 'Compra registrada correctamente.');
    }

    public function edit(Compra $compra)
    {
        if ($compra->estado !== 'pendiente') {
            return redirect()->route('compras.index')
                ->with('error', 'Solo se pueden editar compras pendientes.');
        }

        $compra->load('detalles');
        $proveedores = Proveedor::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();

        return view('compras.edit', compact(
            'compra', 'proveedores', 'ingredientes', 'usuarios'
        ));
    }

    public function update(Request $request, Compra $compra)
{
    $request->merge([
        'detalles' => array_values(array_filter(
            $request->input('detalles', []),
            fn ($detalle) => !empty($detalle['ingrediente_id'])
        )),
    ]);

    $datos = $this->validarCompra($request);
        

        DB::transaction(function () use ($compra, $datos) {
            $compra = Compra::whereKey($compra->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($compra->estado !== 'pendiente') {
                throw ValidationException::withMessages([
                    'compra' => 'Solo se pueden editar compras pendientes.',
                ]);
            }

            $compra->proveedor_id = $datos['proveedor_id'];
            $compra->user_id = $datos['user_id'];
            $compra->fecha_compra = $datos['fecha_compra'];
            $compra->observaciones = $datos['observaciones'] ?? null;
            $compra->save();

            $compra->detalles()->delete();

            $compra->total = $this->guardarDetalles(
                $compra,
                $datos['detalles']
            );

            $compra->save();
        });

        return redirect()->route('compras.index')
            ->with('success', 'Compra actualizada correctamente.');
    }

    public function recibir(Compra $compra)
    {
        DB::transaction(function () use ($compra) {
            $compra = Compra::whereKey($compra->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($compra->estado !== 'pendiente') {
                throw ValidationException::withMessages([
                    'compra' => 'Esta compra ya fue procesada.',
                ]);
            }

            $compra->load('detalles');

            foreach ($compra->detalles as $detalle) {
                $ingrediente = Ingrediente::findOrFail(
                    $detalle->ingrediente_id
                );

                $ingrediente->stock += $detalle->cantidad;
                $ingrediente->save();
            }

            $compra->estado = 'recibida';
            $compra->save();
        });

        return redirect()->route('compras.index')
            ->with('success', 'Compra recibida e inventario actualizado.');
    }

    public function destroy(Compra $compra)
    {
        DB::transaction(function () use ($compra) {
            $compra = Compra::whereKey($compra->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($compra->estado !== 'pendiente') {
                throw ValidationException::withMessages([
                    'compra' => 'Solo se pueden eliminar compras pendientes.',
                ]);
            }

            $compra->detalles()->delete();
            $compra->delete();
        });

        return redirect()->route('compras.index')
            ->with('success', 'Compra eliminada correctamente.');
    }
}