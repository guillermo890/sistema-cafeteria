<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Registrar venta</title>
</head>

<body class="bg-gray-100 p-8">

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-6">Registrar nueva venta</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block font-semibold mb-2">Cliente</label>

                <select name="cliente_id"
                        class="w-full border rounded p-2">

                    <option value="">Venta de mostrador</option>

                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block font-semibold mb-2">Usuario</label>

                <select name="user_id"
                        required
                        class="w-full border rounded p-2">

                    <option value="">Selecciona un usuario</option>

                    @foreach ($usuarios as $usuario)
                        <option value="{{ $usuario->id }}"
                            {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block font-semibold mb-2">Método de pago</label>

                <select name="metodo_pago_id"
                        required
                        class="w-full border rounded p-2">

                    <option value="">Selecciona un método</option>

                    @foreach ($metodos as $metodo)
                        <option value="{{ $metodo->id }}"
                            {{ old('metodo_pago_id') == $metodo->id ? 'selected' : '' }}>
                            {{ $metodo->nombre }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

        <hr class="my-6">

        <h2 class="text-xl font-bold mb-4">Productos de la venta</h2>

        <div id="productos">

            <div class="producto grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">

                <div>
                    <label class="block font-semibold mb-2">Producto</label>

                    <select name="productos[0][producto_id]"
                            required
                            class="w-full border rounded p-2">

                        <option value="">Selecciona un producto</option>

                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}"
                                    data-precio="{{ $producto->precio }}"
                                    data-stock="{{ $producto->stock }}">

                                {{ $producto->nombre }}
                                - ${{ number_format($producto->precio, 2) }}
                                (Stock: {{ $producto->stock }})

                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Cantidad</label>

                    <input type="number"
                           name="productos[0][cantidad]"
                           min="1"
                           value="1"
                           required
                           class="w-full border rounded p-2">
                </div>

                <div class="flex items-end">
                    <button type="button"
                            onclick="eliminarProducto(this)"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Eliminar
                    </button>
                </div>

            </div>

        </div>

        <button type="button"
                onclick="agregarProducto()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            + Agregar producto
        </button>

        <div class="mt-6 text-right">
            <h2 class="text-2xl font-bold">
                Total: $<span id="total">0.00</span>
            </h2>
        </div>

        <div class="flex gap-3 mt-6">

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">
                Registrar venta
            </button>

            <a href="{{ route('ventas.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                Cancelar
            </a>

        </div>

    </form>

</div>

<script>
    let contador = 1;

    function agregarProducto() {

        let contenedor = document.getElementById('productos');

        let original = contenedor.querySelector('.producto');

        let nuevo = original.cloneNode(true);

        let select = nuevo.querySelector('select');

        select.name = 'productos[' + contador + '][producto_id]';
        select.selectedIndex = 0;

        let cantidad = nuevo.querySelector('input');

        cantidad.name = 'productos[' + contador + '][cantidad]';
        cantidad.value = 1;

        contenedor.appendChild(nuevo);

        contador++;

        calcularTotal();
    }

    function eliminarProducto(boton) {

        let contenedor = document.getElementById('productos');

        if (contenedor.querySelectorAll('.producto').length > 1) {

            boton.closest('.producto').remove();

            calcularTotal();
        }
    }

    function calcularTotal() {

        let total = 0;

        document.querySelectorAll('.producto').forEach(function(fila) {

            let select = fila.querySelector('select');

            let cantidad = fila.querySelector('input');

            let opcion = select.options[select.selectedIndex];

            if (opcion && opcion.dataset.precio) {

                let precio = parseFloat(opcion.dataset.precio);

                let stock = parseInt(opcion.dataset.stock);

                let unidades = parseInt(cantidad.value) || 0;

                if (unidades > stock) {
                    cantidad.setCustomValidity(
                        'Solo hay ' + stock + ' unidades disponibles.'
                    );
                } else {
                    cantidad.setCustomValidity('');
                }

                total += precio * unidades;
            }
        });

        document.getElementById('total').textContent =
            total.toFixed(2);
    }

    document.addEventListener('change', function(event) {

        if (event.target.matches('.producto select')) {
            calcularTotal();
        }

    });

    document.addEventListener('input', function(event) {

        if (event.target.matches('.producto input')) {
            calcularTotal();
        }

    });

    calcularTotal();
</script>

</body>
</html>
