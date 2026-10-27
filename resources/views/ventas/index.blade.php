<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Ventas</title>
</head>
<body class="bg-gray-100 p-8">
<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow">
@include('partials.menu')
    <h1 class="text-2xl font-bold mb-6">Ventas</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('ventas.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded">
        Registrar nueva venta
    </a>

    <div class="overflow-x-auto mt-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Cliente</th>
                    <th class="p-3 border">Usuario</th>
                    <th class="p-3 border">Método de pago</th>
                    <th class="p-3 border">Fecha</th>
                    <th class="p-3 border">Total</th>
                    <th class="p-3 border">Estado</th>
                    <th class="p-3 border">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventas as $venta)
                    <tr class="text-center">
                        <td class="p-3 border">{{ $venta->id }}</td>
                        <td class="p-3 border">
                            {{ $venta->cliente->nombre ?? 'Venta de mostrador' }}
                        </td>
                        <td class="p-3 border">
                            {{ $venta->usuario->name ?? 'Sin usuario' }}
                        </td>
                        <td class="p-3 border">
                            {{ $venta->metodoPago->nombre ?? 'Sin método' }}
                        </td>
                        <td class="p-3 border">{{ $venta->fecha_venta }}</td>
                        <td class="p-3 border">
                            ${{ number_format($venta->total, 2) }}
                        </td>
                        <td class="p-3 border">{{ $venta->estado }}</td>
                        <td class="p-3 border">
                            <a href="{{ route('ventas.show', $venta->id) }}"
                               class="text-blue-600 hover:underline">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center">
                            No hay ventas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
