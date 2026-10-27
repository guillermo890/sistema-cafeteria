
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto - Cafetería</title>
</head>
<body>

    <h1>Editar producto</h1>

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">Seleccione una categoría</option>

                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        @selected(old('categoria_id', $producto->categoria_id) == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                   value="{{ old('nombre', $producto->nombre) }}"
                   maxlength="100" required>
        </div>

        <br>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"
                      rows="4">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        <br>

        <div>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio"
                   min="0.01" step="0.01"
                   value="{{ old('precio', $producto->precio) }}" required>
        </div>

        <br>

        <div>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock"
                   min="0" step="1"
                   value="{{ old('stock', $producto->stock) }}" required>
        </div>

        <br>

        <button type="submit">Guardar cambios</button>

        <a href="{{ route('productos.index') }}">Cancelar</a>
    </form>

</body>
</html>
