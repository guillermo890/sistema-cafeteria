<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar categoría - Cafetería</title>
</head>
<body>

    <h1>Editar categoría</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.update', $categoria->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div>
            <label for="nombre">Nombre:</label>
            <input
                type="text"
                id="nombre"
                name="nombre"
                value="{{ old('nombre', $categoria->nombre) }}"
                required
                maxlength="100"
            >
        </div>

        <br>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea
                id="descripcion"
                name="descripcion"
                rows="4"
            >{{ old('descripcion', $categoria->descripcion) }}</textarea>
        </div>

        <br>

        <button type="submit">Guardar cambios</button>

        <a href="{{ route('categorias.index') }}">
            Cancelar
        </a>

    </form>

</body>
</html>
