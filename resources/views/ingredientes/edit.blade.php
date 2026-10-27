<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ingrediente</title>
</head>
<body>

<h1>Editar ingrediente</h1>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('ingredientes.update', $ingrediente) }}"
      method="POST">
    @csrf
    @method('PUT')

    <p>
        <label>Nombre:</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $ingrediente->nombre) }}"
               required>
    </p>

    <p>
        <label>Unidad de medida:</label>
        <select name="unidad_medida" required>
            @foreach(['gramos', 'kilogramos', 'mililitros', 'litros', 'piezas'] as $unidad)
                <option value="{{ $unidad }}"
                    @selected(old('unidad_medida', $ingrediente->unidad_medida) == $unidad)>
                    {{ ucfirst($unidad) }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Stock:</label>
        <input type="number" name="stock" min="0" step="0.01"
               value="{{ old('stock', $ingrediente->stock) }}"
               required>
    </p>

    <p>
        <label>Stock mínimo:</label>
        <input type="number" name="stock_minimo" min="0" step="0.01"
               value="{{ old('stock_minimo', $ingrediente->stock_minimo) }}"
               required>
    </p>

    <button type="submit">Guardar cambios</button>

    <a href="{{ route('ingredientes.index') }}">Cancelar</a>
</form>

</body>
</html>
