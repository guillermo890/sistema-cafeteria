
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores - Cafetería</title>
</head>
<body>

<h1>Gestión de proveedores</h1>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<h2>Registrar proveedor</h2>

<form action="{{ route('proveedores.store') }}" method="POST">
    @csrf

    <p>
        <label>Nombre:</label>
        <input type="text" name="nombre"
               value="{{ old('nombre') }}" maxlength="100" required>
    </p>

    <p>
        <label>Teléfono:</label>
        <input type="tel" name="telefono"
               value="{{ old('telefono') }}"
               maxlength="10" pattern="[0-9]{10}">
    </p>

    <p>
        <label>Correo electrónico:</label>
        <input type="email" name="email"
               value="{{ old('email') }}">
    </p>

    <p>
        <label>Dirección:</label>
        <input type="text" name="direccion"
               value="{{ old('direccion') }}">
    </p>

    <button type="submit">Guardar proveedor</button>
</form>

<hr>

<h2>Lista de proveedores</h2>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($proveedores as $proveedor)
            <tr>
                <td>{{ $proveedor->id }}</td>
                <td>{{ $proveedor->nombre }}</td>
                <td>{{ $proveedor->telefono }}</td>
                <td>{{ $proveedor->email }}</td>
                <td>{{ $proveedor->direccion }}</td>
                <td>
                    <a href="{{ route('proveedores.edit', $proveedor) }}">
                        Editar
                    </a>

                    <form action="{{ route('proveedores.destroy', $proveedor) }}"
                          method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            onclick="return confirm('¿Eliminar proveedor?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No hay proveedores registrados.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
