
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Compras - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-4 md:p-8">

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Gestión de compras
        </h1>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Botón para registrar compra --}}
        <div class="mb-6">
            <a href="{{ route('compras.create') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Registrar nueva compra
            </a>
        </div>

        <h2 class="text-xl font-semibold mb-4">
            Lista de compras
        </h2>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm">

                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-center">ID</th>
                        <th class="p-3 text-center">Fecha</th>
                        <th class="p-3 text-center">Proveedor</th>
                        <th class="p-3 text-center">Usuario</th>
                        <th class="p-3 text-center">Total</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-center">Ingredientes</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($compras as $compra)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3 text-center">
                                {{ $compra->id }}
                            </td>

                            <td class="p-3 text-center whitespace-nowrap">
                                {{ $compra->fecha_compra }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $compra->proveedor?->nombre ?? 'Sin proveedor' }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $compra->usuario?->name ?? 'Sin usuario' }}
                            </td>

                            <td class="p-3 text-center font-semibold whitespace-nowrap">
                                ${{ number_format($compra->total, 2) }}
                            </td>

                            <td class="p-3 text-center">
                                @if($compra->estado === 'pendiente')
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium">
                                        Pendiente
                                    </span>
                                @elseif($compra->estado === 'recibida')
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
                                        Recibida
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full">
                                        {{ ucfirst($compra->estado) }}
                                    </span>
                                @endif
                            </td>

                            <td class="p-3">
                                <ul class="space-y-2">
                                    @foreach($compra->detalles as $detalle)
                                        <li class="border-b border-gray-100 pb-1 last:border-0">
                                            <span class="font-medium">
                                                {{ $detalle->ingrediente?->nombre ?? 'Ingrediente no disponible' }}
                                            </span>

                                            <div class="text-gray-600 text-xs">
                                                Cantidad: {{ $detalle->cantidad }}
                                                · Subtotal:
                                                ${{ number_format($detalle->subtotal, 2) }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            <td class="p-3">
                                @if($compra->estado === 'pendiente')
                                    <div class="flex flex-wrap justify-center gap-2">

                                        <a href="{{ route('compras.edit', $compra) }}"
                                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                            Editar
                                        </a>

                                        <form action="{{ route('compras.recibir', $compra) }}"
                                              method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    onclick="return confirm('¿Confirmar recepción de ingredientes?')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                                Recibir
                                            </button>
                                        </form>

                                        <form action="{{ route('compras.destroy', $compra) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    onclick="return confirm('¿Eliminar esta compra pendiente?')"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>

                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">
                                        Sin acciones disponibles
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="p-6 text-center text-gray-500">
                                No hay compras registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</body>
</html>
