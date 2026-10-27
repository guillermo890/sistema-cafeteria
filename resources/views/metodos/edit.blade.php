<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Editar método</title>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">Editar método de pago</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('metodos.update', $metodo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label class="block mb-2">Nombre</label>
        <input type="text" name="nombre"
            value="{{ old('nombre', $metodo->nombre) }}"
            class="w-full border p-2 rounded mb-4">

        <label class="block mb-2">Estado</label>
        <select name="estado" class="w-full border p-2 rounded mb-4">
            <option value="1" {{ old('estado', $metodo->estado) == 1 ? 'selected' : '' }}>
                Activo
            </option>
            <option value="0" {{ old('estado', $metodo->estado) == 0 ? 'selected' : '' }}>
                Inactivo
            </option>
        </select>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar cambios
        </button>

        <a href="{{ route('metodos.index') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded">
            Cancelar
        </a>
    </form>

</div>

</body>
</html>
