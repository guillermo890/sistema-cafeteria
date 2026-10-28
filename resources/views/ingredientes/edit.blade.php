<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Editar ingrediente - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Editar ingrediente
        </h1>

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ingredientes.update', $ingrediente) }}"
            method="POST"
            novalidate
            class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @csrf
            @method('PUT')

            <div>
                <label for="nombre"
                       class="block font-medium text-gray-700 mb-2">
                    Nombre:
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre', $ingrediente->nombre) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('nombre')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
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

                    @foreach(['gramos', 'kilogramos', 'mililitros', 'litros', 'piezas'] as $unidad)
                        <option value="{{ $unidad }}"
                            @selected(old('unidad_medida', $ingrediente->unidad_medida) == $unidad)>
                            {{ ucfirst($unidad) }}
                        </option>
                    @endforeach

                </select>

                @error('unidad_medida')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="precio"
                       class="block font-medium text-gray-700 mb-2">
                    Precio:
                </label>

                <input
                    type="number"
                    id="precio"
                    name="precio"
                    min="0.01"
                    step="0.01"
                    value="{{ old('precio', $ingrediente->precio) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('precio')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
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
                    value="{{ old('stock', $ingrediente->stock) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('stock')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
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
                    value="{{ old('stock_minimo', $ingrediente->stock_minimo) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('stock_minimo')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3 mt-4">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar cambios
                </button>

                <a href="{{ route('ingredientes.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</body>
</html>
