
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Cafetería</title>
</head>
<body>

    <h1>Gestión de productos</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul style="color: red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Registrar producto</h2>

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf

        <div>
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">Seleccione una categoría</option>

                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                        @selected(old('categoria_id') == $categoria->id)>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"
                   value="{{ old('nombre') }}"
                   maxlength="100" required>
        </div>

        <br>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
        </div>

        <br>

        <div>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio"
                   min="0.01" step="0.01"
                   value="{{ old('precio') }}" required>
        </div>

        <br>

        <div>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock"
                   min="0" step="1"
                   value="{{ old('stock', 0) }}" required>
        </div>

        <br>

        <button type="submit">Guardar producto</button>
    </form>

    <hr>

    <h2>Lista de productos</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>
                        <a href="{{ route('productos.edit', $producto) }}">
                            Editar
                        </a>

                        <form action="{{ route('productos.destroy', $producto) }}"
                              method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                onclick="return confirm('¿Eliminar producto?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
