<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Editar producto - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Editar producto
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

        <form action="{{ route('productos.update', $producto) }}"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @csrf
            @method('PUT')

            <div>
                <label for="categoria_id"
                       class="block font-medium text-gray-700 mb-2">
                    Categoría:
                </label>

                <select name="categoria_id"
                        id="categoria_id"
                        required
                        class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="">Seleccione una categoría</option>

                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}"
                            @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label for="nombre"
                       class="block font-medium text-gray-700 mb-2">
                    Nombre:
                </label>

                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre', $producto->nombre) }}"
                       maxlength="100"
                       required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label for="descripcion"
                       class="block font-medium text-gray-700 mb-2">
                    Descripción:
                </label>

                <textarea id="descripcion"
                          name="descripcion"
                          rows="4"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div>
                <label for="precio"
                       class="block font-medium text-gray-700 mb-2">
                    Precio:
                </label>

                <input type="number"
                       id="precio"
                       name="precio"
                       min="0.01"
                       step="0.01"
                       value="{{ old('precio', $producto->precio) }}"
                       required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="stock"
                       class="block font-medium text-gray-700 mb-2">
                    Stock:
                </label>

                <input type="number"
                       id="stock"
                       name="stock"
                       min="0"
                       step="1"
                       value="{{ old('stock', $producto->stock) }}"
                       required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3 mt-4">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar cambios
                </button>

                <a href="{{ route('productos.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</body>
</html>
