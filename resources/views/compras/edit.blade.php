<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar compra - Cafetería</title>
</head>
<body>

<h1>Editar compra #{{ $compra->id }}</h1>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('compras.update', $compra) }}" method="POST">
    @csrf
    @method('PUT')

    <p>
        <label>Proveedor:</label>
        <select name="proveedor_id" required>
            <option value="">Seleccione proveedor</option>
            @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}"
                    @selected(old('proveedor_id', $compra->proveedor_id) == $proveedor->id)>
                    {{ $proveedor->nombre }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Usuario responsable:</label>
        <select name="user_id" required>
            @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}"
                    @selected(old('user_id', $compra->user_id) == $usuario->id)>
                    {{ $usuario->name }}
                </option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Fecha:</label>
        <input type="date" name="fecha_compra"
            value="{{ old('fecha_compra', $compra->fecha_compra) }}"
            required>
    </p>

    <h2>Ingredientes</h2>

    @php
        $detalles = old('detalles',
            $compra->detalles->map(fn($d) => [
                'ingrediente_id' => $d->ingrediente_id,
                'cantidad' => $d->cantidad,
                'precio_unitario' => $d->precio_unitario,
            ])->toArray()
        );
    @endphp

    @for($i = 0; $i < 3; $i++)
        <fieldset>
            <legend>Ingrediente {{ $i + 1 }}</legend>

            <label>Ingrediente:</label>
            <select name="detalles[{{ $i }}][ingrediente_id]"
                    @if($i === 0) required @endif>
                <option value="">Seleccione ingrediente</option>

                @foreach($ingredientes as $ingrediente)
                    <option value="{{ $ingrediente->id }}"
                        @selected(($detalles[$i]['ingrediente_id'] ?? '') == $ingrediente->id)>
                        {{ $ingrediente->nombre }}
                        ({{ $ingrediente->unidad_medida }})
                    </option>
                @endforeach
            </select>

            <label>Cantidad:</label>
            <input type="number"
                name="detalles[{{ $i }}][cantidad]"
                min="0.01" step="0.01"
                value="{{ $detalles[$i]['cantidad'] ?? '' }}"
                @if($i === 0) required @endif>

            <label>Precio unitario:</label>
            <input type="number"
                name="detalles[{{ $i }}][precio_unitario]"
                min="0" step="0.01"
                value="{{ $detalles[$i]['precio_unitario'] ?? '' }}"
                @if($i === 0) required @endif>
        </fieldset>
        <br>
    @endfor

    <p>
        <label>Observaciones:</label>
        <textarea name="observaciones"
            rows="3">{{ old('observaciones', $compra->observaciones) }}</textarea>
    </p>

    <button type="submit">Guardar cambios</button>

    <a href="{{ route('compras.index') }}">Cancelar</a>
</form>

</body>
</html>
