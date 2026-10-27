<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Métodos de pago</title>
</head>
<body class="bg-gray-100 p-8">
<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
@include('partials.menu')
    <h1 class="text-2xl font-bold mb-6">Métodos de pago</h1>

    @if(session('success'))
        <p class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </p>
    @endif

    <a href="{{ route('metodos.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded">
        Registrar método de pago
    </a>

    <table class="w-full mt-6 border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Nombre</th>
                <th class="p-3">Estado</th>
                <th class="p-3">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($metodos as $metodo)
                <tr class="border-t text-center">
                    <td class="p-3">{{ $metodo->id }}</td>
                    <td class="p-3">{{ $metodo->nombre }}</td>

                    <td class="p-3">
                        {{ $metodo->estado ? 'Activo' : 'Inactivo' }}
                    </td>

                    <td class="p-3">
                        <a href="{{ route('metodos.edit', $metodo->id) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded">
                            Editar
                        </a>

                        <form action="{{ route('metodos.destroy', $metodo->id) }}"
                            method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                onclick="return confirm('¿Eliminar este método de pago?')"
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
