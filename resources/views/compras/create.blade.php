
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar compra - Cafetería</title>
</head>
<body>

<h1>Registrar nueva compra</h1>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('compras.store') }}" method="POST">
    @csrf

    <p>
        <label>Proveedor:</label>
        <select name="proveedor_id" required>
            <option value="">Seleccione un proveedor</option>
            @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}"
                    @selected(old('proveedor_id') == $proveedor->id)>
                    {{ $proveedor->nombre }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Usuario responsable:</label>
        <select name="user_id" required>
            <option value="">Seleccione un usuario</option>
            @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}"
                    @selected(old('user_id') == $usuario->id)>
                    {{ $usuario->name }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Fecha de compra:</label>
        <input type="date" name="fecha_compra"
               value="{{ old('fecha_compra', date('Y-m-d')) }}"
               required>
    </p>

    <h2>Ingredientes de la compra</h2>

    <p>Selecciona hasta tres ingredientes diferentes.</p>

    @for($i = 0; $i < 3; $i++)
        <fieldset>
            <legend>Ingrediente {{ $i + 1 }}</legend>

            <label>Ingrediente:</label>
            <select name="detalles[{{ $i }}][ingrediente_id]"
                    @if($i === 0) required @endif>
                <option value="">Seleccione ingrediente</option>
                @foreach($ingredientes as $ingrediente)
                    <option value="{{ $ingrediente->id }}"
                        @selected(old("detalles.$i.ingrediente_id") == $ingrediente->id)>
                        {{ $ingrediente->nombre }}
                        ({{ $ingrediente->unidad_medida }})
                    </option>
                @endforeach
            </select>

            <label>Cantidad:</label>
            <input type="number"
                   name="detalles[{{ $i }}][cantidad]"
                   min="0.01" step="0.01"
                   value="{{ old("detalles.$i.cantidad") }}"
                   @if($i === 0) required @endif>

            <label>Precio unitario:</label>
            <input type="number"
                   name="detalles[{{ $i }}][precio_unitario]"
                   min="0" step="0.01"
                   value="{{ old("detalles.$i.precio_unitario") }}"
                   @if($i === 0) required @endif>
        </fieldset>
        <br>
    @endfor

    <p>
        <label>Observaciones:</label>
        <textarea name="observaciones"
                  rows="3">{{ old('observaciones') }}</textarea>
    </p>

    <button type="submit">Guardar compra</button>

    <a href="{{ route('compras.index') }}">Cancelar</a>
</form>

</body>
</html>
