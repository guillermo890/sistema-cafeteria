<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredientes - Cafetería</title>
</head>
<body>
@include('partials.menu')
<h1>Gestión de ingredientes</h1>

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

<h2>Registrar ingrediente</h2>

<form action="{{ route('ingredientes.store') }}" method="POST">
    @csrf

    <p>
        <label>Nombre:</label>
        <input type="text" name="nombre"
               value="{{ old('nombre') }}" required>
    </p>

    <p>
        <label>Unidad de medida:</label>
        <select name="unidad_medida" required>
            <option value="">Seleccione una unidad</option>
            @foreach(['gramos', 'kilogramos', 'mililitros', 'litros', 'piezas'] as $unidad)
                <option value="{{ $unidad }}" @selected(old('unidad_medida') == $unidad)>
                    {{ ucfirst($unidad) }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Stock:</label>
        <input type="number" name="stock" step="0.01"
               min="0" value="{{ old('stock', 0) }}" required>
    </p>

    <p>
        <label>Stock mínimo:</label>
        <input type="number" name="stock_minimo" step="0.01"
               min="0" value="{{ old('stock_minimo', 0) }}" required>
    </p>

    <button type="submit">Guardar ingrediente</button>
</form>

<hr>

<h2>Lista de ingredientes</h2>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Unidad</th>
            <th>Stock</th>
            <th>Stock mínimo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ingredientes as $ingrediente)
            <tr>
                <td>{{ $ingrediente->id }}</td>
                <td>{{ $ingrediente->nombre }}</td>
                <td>{{ $ingrediente->unidad_medida }}</td>
                <td>{{ $ingrediente->stock }}</td>
                <td>{{ $ingrediente->stock_minimo }}</td>
                <td>
                    <a href="{{ route('ingredientes.edit', $ingrediente) }}">
                        Editar
                    </a>

                    <form action="{{ route('ingredientes.destroy', $ingrediente) }}"
                          method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            onclick="return confirm('¿Eliminar ingrediente?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No hay ingredientes registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
