
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Categorías - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    @include('partials.menu')

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Gestión de categorías
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

        {{-- Formulario para registrar --}}
        <h2 class="text-xl font-semibold mb-4">
            Registrar categoría
        </h2>

        <form action="{{ route('categorias.store') }}"
              method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

            @csrf

            <div>
                <label for="nombre"
                       class="block font-medium mb-2">
                    Nombre:
                </label>

                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="descripcion"
                       class="block font-medium mb-2">
                    Descripción:
                </label>

                <textarea id="descripcion"
                          name="descripcion"
                          rows="2"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar categoría
                </button>
            </div>
        </form>

        {{-- Tabla de categorías --}}
        <h2 class="text-xl font-semibold mb-4">
            Lista de categorías
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-center">ID</th>
                        <th class="p-3 text-center">Nombre</th>
                        <th class="p-3 text-center">Descripción</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categorias as $categoria)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 text-center">
                                {{ $categoria->id }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $categoria->nombre }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $categoria->descripcion }}
                            </td>

                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2 flex-wrap">

                                    <a href="{{ route('categorias.edit', $categoria) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Editar
                                    </a>

                                    <form action="{{ route('categorias.destroy', $categoria) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                onclick="return confirm('¿Eliminar categoría?')"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="p-6 text-center text-gray-500">
                                No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
