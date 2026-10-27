
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Cafetería</title>
</head>
<body>
    <h1>Gestión de categorías</h1>

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

    <h2>Registrar categoría</h2>

    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf

        <label>Nombre:</label>
        <input type="text" name="nombre"
               value="{{ old('nombre') }}" required>

        <label>Descripción:</label>
        <textarea name="descripcion">{{ old('descripcion') }}</textarea>

        <button type="submit">Guardar categoría</button>
    </form>

    <h2>Lista de categorías</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                    <td>{{ $categoria->descripcion }}</td>
                    <td>
                        <a href="{{ route('categorias.edit', $categoria) }}">
                            Editar
                        </a>

                        <form action="{{ route('categorias.destroy', $categoria) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                onclick="return confirm('¿Eliminar categoría?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay categorías registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
