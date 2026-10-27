
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Usuarios</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto p-8">
        <div class="bg-white rounded-xl shadow-md p-6">

            <h1 class="text-3xl font-bold mb-6">Administración de Usuarios</h1>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <table class="w-full text-left mt-6">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-3">ID</th>
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Correo</th>
                        <th class="p-3">Rol</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr class="border-b">
                            <td class="p-3">{{ $usuario->id }}</td>
                            <td class="p-3">{{ $usuario->name }}</td>
                            <td class="p-3">{{ $usuario->email }}</td>
                            <td class="p-3">{{ $usuario->rol->nombre ?? 'Sin rol' }}</td>

                            <td class="p-3">
                                <a href="{{ route('usuarios.edit', $usuario->id) }}"
                                    class="bg-yellow-500 text-white px-3 py-2 rounded-lg">
                                    Editar
                                </a>

                                <form action="{{ route('usuarios.destroy', $usuario->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        onclick="return confirm('¿Eliminar este usuario?')"
                                        class="bg-red-600 text-white px-3 py-2 rounded-lg">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    
            <a href="{{ route('usuarios.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Registrar usuario
            </a>
                </tbody>
            </table>
        </div>
    </div> 
</body>
</html>
