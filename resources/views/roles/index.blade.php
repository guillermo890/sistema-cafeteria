<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Roles</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-6xl mx-auto p-8">

        <div class="bg-white rounded-xl shadow-md p-6">

            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Administración de Roles
            
            </h1>
            
               @if (session('success'))
                <div class= "bg-green-100 text-green-700 p-3 rounded-lg mb-4">
                    {{ session('success')}}
                </div>
                @endif

            <p class="text-gray-500 mb-6">
                Consulta y administra los roles del sistema.
            </p>

            <div class="overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>
                        <tr class="bg-gray-800 text-white">
                        
                            <th class="p-4">ID</th>
                            <th class="p-4">Nombre</th>
                            <th class="p-4">Descripción</th>
                             <th class="p-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($roles as $rol)
                    <tr>
                    <td>{{ $rol->id }}</td>
                    <td>{{ $rol->nombre }}</td>
                    <td>{{ $rol->descripcion }}</td>
                    <td class="p-4">
                    <a href="{{ route('roles.edit', $rol->id) }}"
                       class="bg-yellow-500 text-white px-3 py-2 rounded-lg hover:bg-yellow-600">
                       Editar
                       </a>
                       <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" class="inline">
                         @csrf
                        @method('DELETE')

                    <button type="submit"
                    onclick="return confirm('¿Estás seguro de eliminar este rol?')"
                    class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700">
                     Eliminar
                    </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
                </table>

            </div>
             <a href="{{ route('roles.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
             Registrar nuevo rol
            </a>
        </div>

    </div>

</body>
</html>