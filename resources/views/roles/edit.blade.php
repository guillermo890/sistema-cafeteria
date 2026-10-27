<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rol</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-xl mx-auto p-8">
        <div class="bg-white rounded-xl shadow-md p-6">

            <h1 class="text-2xl font-bold text-gray-800 mb-6">
                Editar Rol
            </h1>
            @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
                @endforeach
            </div>
                @endif
           <form action="{{ route('roles.update', $rol->id) }}" method="POST">
                 @csrf
                @method('PUT')
                <label class="block text-gray-700 mb-2">Nombre del rol</label>

                <input type="text" name="nombre"
                    value="{{ $rol->nombre }}"
                    class="w-full border rounded-lg p-3 mb-4">

                <label class="block text-gray-700 mb-2">Descripción</label>

                <textarea name="descripcion"
                    class="w-full border rounded-lg p-3 mb-6">{{ $rol->descripcion }}</textarea>

                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                    Actualizar rol
                </button>

                <a href="{{ route('roles.index') }}"
                    class="ml-3 text-gray-600 hover:underline">
                    Cancelar
                </a>
            </form>

        </div>
    </div>

</body>
</html>
