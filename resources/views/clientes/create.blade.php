
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Registrar cliente</title>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">Registrar cliente</h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <label class="block mb-2">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono') }}"
                class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion') }}"
                class="w-full border p-2 rounded mb-4">

            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Guardar cliente
            </button>

            <a href="{{ route('clientes.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Cancelar
            </a>
        </form>

    </div>

</body>
</html>
