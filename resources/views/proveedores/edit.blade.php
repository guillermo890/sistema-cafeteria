
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar proveedor - Cafetería</title>
</head>
<body>

<h1>Editar proveedor</h1>

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
    @csrf
    @method('PUT')

    <p>
        <label>Nombre:</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $proveedor->nombre) }}"
               maxlength="100" required>
    </p>

    <p>
        <label>Teléfono:</label>
        <input type="tel" name="telefono"
               value="{{ old('telefono', $proveedor->telefono) }}"
               maxlength="10" pattern="[0-9]{10}">
    </p>

    <p>
        <label>Correo electrónico:</label>
        <input type="email" name="email"
               value="{{ old('email', $proveedor->email) }}">
    </p>

    <p>
        <label>Dirección:</label>
        <input type="text" name="direccion"
               value="{{ old('direccion', $proveedor->direccion) }}">
    </p>

    <button type="submit">Guardar cambios</button>
    <a href="{{ route('proveedores.index') }}">Cancelar</a>
</form>

</body>
</html>
