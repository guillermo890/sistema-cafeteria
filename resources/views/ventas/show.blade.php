<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Detalle de venta</title>
</head>

<body class="bg-gray-100 p-8">

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">
        Detalle de venta #{{ $venta->id }}
    </h1>

    <div class="bg-gray-100 p-4 rounded mb-6">

        <p class="mb-2">
            <strong>Cliente:</strong>
            {{ $venta->cliente->nombre ?? 'Venta de mostrador' }}
        </p>

        <p class="mb-2">
            <strong>Usuario:</strong>
            {{ $venta->usuario->name ?? 'Sin usuario' }}
        </p>

        <p class="mb-2">
            <strong>Método de pago:</strong>
            {{ $venta->metodoPago->nombre ?? 'Sin método' }}
        </p>

        <p class="mb-2">
            <strong>Fecha:</strong>
            {{ $venta->fecha_venta }}
        </p>

        <p>
            <strong>Estado:</strong>
            {{ $venta->estado }}
        </p>

    </div>

    <h2 class="text-xl font-bold mb-4">Productos vendidos</h2>

    <div class="overflow-x-auto">

        <table class="w-full border-collapse">

            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border">Producto</th>
                    <th class="p-3 border">Cantidad</th>
                    <th class="p-3 border">Precio unitario</th>
                    <th class="p-3 border">Subtotal</th>
                </tr>
            </thead>

            <tbody>

                @forelse($venta->detalles as $detalle)

                    <tr class="text-center">

                        <td class="p-3 border">
                            {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                        </td>

                        <td class="p-3 border">
                            {{ $detalle->cantidad }}
                        </td>

                        <td class="p-3 border">
                            ${{ number_format($detalle->precio_unitario, 2) }}
                        </td>

                        <td class="p-3 border">
                            ${{ number_format($detalle->subtotal, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="p-4 text-center">
                            No hay productos registrados en esta venta.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="text-right mt-6">

        <h2 class="text-2xl font-bold">
            Total: ${{ number_format($venta->total, 2) }}
        </h2>

    </div>

    <a href="{{ route('ventas.index') }}"
       class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded mt-6">
        Regresar a ventas
    </a>

</div>

</body>
</html>
