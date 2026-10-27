
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Proveedores - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Gestión de proveedores
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

        {{-- Formulario para registrar proveedores --}}
        <h2 class="text-xl font-semibold mb-4">
            Registrar proveedor
        </h2>

        <form action="{{ route('proveedores.store') }}"
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
                    maxlength="100"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="telefono"
                       class="block font-medium text-gray-700 mb-2">
                    Teléfono:
                </label>

                <input
                    type="tel"
                    id="telefono"
                    name="telefono"
                    value="{{ old('telefono') }}"
                    maxlength="10"
                    pattern="[0-9]{10}"
                    inputmode="numeric"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="email"
                       class="block font-medium text-gray-700 mb-2">
                    Correo electrónico:
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="direccion"
                       class="block font-medium text-gray-700 mb-2">
                    Dirección:
                </label>

                <input
                    type="text"
                    id="direccion"
                    name="direccion"
                    value="{{ old('direccion') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar proveedor
                </button>
            </div>

        </form>

        {{-- Tabla de proveedores --}}
        <h2 class="text-xl font-semibold mb-4">
            Lista de proveedores
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-center">ID</th>
                        <th class="p-3 text-center">Nombre</th>
                        <th class="p-3 text-center">Teléfono</th>
                        <th class="p-3 text-center">Correo</th>
                        <th class="p-3 text-center">Dirección</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($proveedores as $proveedor)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 text-center">
                                {{ $proveedor->id }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $proveedor->nombre }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $proveedor->telefono }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $proveedor->email }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $proveedor->direccion }}
                            </td>

                            <td class="p-3">
                                <div class="flex flex-wrap justify-center gap-2">

                                    <a href="{{ route('proveedores.edit', $proveedor) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                                        Editar
                                    </a>

                                    <form action="{{ route('proveedores.destroy', $proveedor) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('¿Eliminar proveedor?')"
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
                                No hay proveedores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
