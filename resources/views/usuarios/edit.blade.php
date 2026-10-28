<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-xl mx-auto p-8">
        <div class="bg-white rounded-xl shadow-md p-6">

            <h1 class="text-2xl font-bold mb-6">Editar Usuario</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <label class="block mb-2">Nombre</label>
                <input type="text" name="name" value="{{ $usuario->name }}"
                    class="w-full border rounded-lg p-3 mb-4">

                <label class="block mb-2">Correo electrónico</label>
                <input type="email" name="email" value="{{ $usuario->email }}"
                    class="w-full border rounded-lg p-3 mb-4">

                <label class="block mb-2">Nueva contraseña (opcional)</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg p-3 mb-4">
                    <label class="block mb-2">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation"
                    class="w-full border rounded-lg p-3 mb-4">

                <label class="block mb-2">Rol</label>
                <select name="rol_id" class="w-full border rounded-lg p-3 mb-6">
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id }}"
                            {{ $usuario->rol_id == $rol->id ? 'selected' : '' }}>
                            {{ $rol->nombre }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg">
                    Actualizar usuario
                </button>

                <a href="{{ route('usuarios.index') }}" class="ml-3 text-gray-600">
                    Cancelar
                </a>
            </form>

        </div>
    </div>
</body>
</html>
