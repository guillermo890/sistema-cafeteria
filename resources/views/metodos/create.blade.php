<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Registrar método</title>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">Registrar método de pago</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('metodos.store') }}" method="POST">
        @csrf

        <label class="block mb-2">Nombre</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}"
            class="w-full border p-2 rounded mb-4">

        <label class="block mb-2">Estado</label>
        <select name="estado" class="w-full border p-2 rounded mb-4">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar
        </button>

        <a href="{{ route('metodos.index') }}"
            class="bg-gray-500 text-white px-4 py-2 rounded">
            Cancelar
        </a>
    </form>

</div>

</body>
</html>
