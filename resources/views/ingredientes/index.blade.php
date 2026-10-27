
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Ingredientes - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Gestión de ingredientes
        </h1>

        {{-- Mensajes de éxito y error --}}
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

        {{-- Formulario de registro --}}
        <h2 class="text-xl font-semibold mb-4">
            Registrar ingrediente
        </h2>

        <form action="{{ route('ingredientes.store') }}"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

            @csrf

            <div>
                <label for="nombre"
                       class="block font-medium text-gray-700 mb-2">
                    Nombre:
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="unidad_medida"
                       class="block font-medium text-gray-700 mb-2">
                    Unidad de medida:
                </label>

                <select
                    name="unidad_medida"
                    id="unidad_medida"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">Seleccione una unidad</option>

                    @foreach(['gramos', 'kilogramos', 'mililitros', 'litros', 'piezas'] as $unidad)
                        <option value="{{ $unidad }}"
                            @selected(old('unidad_medida') == $unidad)>
                            {{ ucfirst($unidad) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="stock"
                       class="block font-medium text-gray-700 mb-2">
                    Stock:
                </label>

                <input
                    type="number"
                    id="stock"
                    name="stock"
                    min="0"
                    step="0.01"
                    value="{{ old('stock', 0) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="stock_minimo"
                       class="block font-medium text-gray-700 mb-2">
                    Stock mínimo:
                </label>

                <input
                    type="number"
                    id="stock_minimo"
                    name="stock_minimo"
                    min="0"
                    step="0.01"
                    value="{{ old('stock_minimo', 0) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar ingrediente
                </button>
            </div>

        </form>

        {{-- Tabla de ingredientes --}}
        <h2 class="text-xl font-semibold mb-4">
            Lista de ingredientes
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-center">ID</th>
                        <th class="p-3 text-center">Nombre</th>
                        <th class="p-3 text-center">Unidad</th>
                        <th class="p-3 text-center">Stock</th>
                        <th class="p-3 text-center">Stock mínimo</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ingredientes as $ingrediente)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 text-center">
                                {{ $ingrediente->id }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $ingrediente->nombre }}
                            </td>

                            <td class="p-3 text-center">
                                {{ ucfirst($ingrediente->unidad_medida) }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $ingrediente->stock }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $ingrediente->stock_minimo }}
                            </td>

                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">

                                    <a href="{{ route('ingredientes.edit', $ingrediente) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Editar
                                    </a>

                                    <form
                                        action="{{ route('ingredientes.destroy', $ingrediente) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('¿Eliminar ingrediente?')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="p-6 text-center text-gray-500">
                                No hay ingredientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
