
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Editar proveedor - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Editar proveedor
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

        <form action="{{ route('proveedores.update', $proveedor) }}"
              method="POST"
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
                    value="{{ old('nombre', $proveedor->nombre) }}"
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
                    value="{{ old('telefono', $proveedor->telefono) }}"
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
                    value="{{ old('email', $proveedor->email) }}"
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
                    value="{{ old('direccion', $proveedor->direccion) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3 mt-4">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar cambios
                </button>

                <a href="{{ route('proveedores.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</body>
</html>
