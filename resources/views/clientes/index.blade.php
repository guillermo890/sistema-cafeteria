
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Clientes</title>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-4">Clientes</h1>

        @if(session('success'))
            <p class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </p>
        @endif

        <a href="{{ route('clientes.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Registrar cliente
        </a>

        <table class="w-full mt-6 border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Correo</th>
                    <th class="p-3">Teléfono</th>
                    <th class="p-3">Dirección</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($clientes as $cliente)
                    <tr class="border-t text-center">
                        <td class="p-3">{{ $cliente->id }}</td>
                        <td class="p-3">{{ $cliente->nombre }}</td>
                        <td class="p-3">{{ $cliente->email }}</td>
                        <td class="p-3">{{ $cliente->telefono }}</td>
                        <td class="p-3">{{ $cliente->direccion }}</td>

                        <td class="p-3">
                            <a href="{{ route('clientes.edit', $cliente->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded">
                                Editar
                            </a>

                            <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    onclick="return confirm('¿Eliminar este cliente?')"
                                    class="bg-red-600 text-white px-3 py-1 rounded">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>
