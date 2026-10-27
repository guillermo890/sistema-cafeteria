<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compras - Cafetería</title>
</head>
<body>
@include('partials.menu')
    <h1>Gestión de compras</h1>

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

    <p>
        <a href="{{ route('compras.create') }}">
            Registrar nueva compra
        </a>
    </p>

    <h2>Lista de compras</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Usuario</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Ingredientes</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($compras as $compra)
                <tr>
                    <td>{{ $compra->id }}</td>
                    <td>{{ $compra->fecha_compra }}</td>
                    <td>{{ $compra->proveedor?->nombre ?? 'Sin proveedor' }}</td>
                    <td>{{ $compra->usuario?->name ?? 'Sin usuario' }}</td>
                    <td>${{ number_format($compra->total, 2) }}</td>
                    <td>{{ ucfirst($compra->estado) }}</td>

                    <td>
                        <ul>
                            @foreach($compra->detalles as $detalle)
                                <li>
                                    {{ $detalle->ingrediente?->nombre }}
                                    - {{ $detalle->cantidad }}
                                    - ${{ number_format($detalle->subtotal, 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    <td>
                        @if($compra->estado === 'pendiente')
                            <a href="{{ route('compras.edit', $compra) }}">
                                Editar
                            </a>

                            <form action="{{ route('compras.recibir', $compra) }}"
                                  method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    onclick="return confirm('¿Confirmar recepción de ingredientes?')">
                                    Recibir
                                </button>
                            </form>

                            <form action="{{ route('compras.destroy', $compra) }}"
                                  method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    onclick="return confirm('¿Eliminar esta compra pendiente?')">
                                    Eliminar
                                </button>
                            </form>
                        @else
                            Sin acciones disponibles
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        No hay compras registradas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
