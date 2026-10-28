<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar compra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">

            <h1 class="text-2xl font-bold mb-6">Registrar compra</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('compras.store') }}" method="POST" id="formCompra">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                    <div>
                        <label class="block font-semibold mb-1">Proveedor</label>
                        <select name="proveedor_id" class="w-full border rounded p-2" required>
                            <option value="">Selecciona un proveedor</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}"
                                    {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Usuario</label>
                        <select name="user_id" class="w-full border rounded p-2" required>
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
                        <label class="block font-semibold mb-1">Fecha de compra</label>
                        <input type="date" name="fecha_compra"
                            value="{{ old('fecha_compra', date('Y-m-d')) }}"
                            class="w-full border rounded p-2" required>
                    </div>

                </div>

                <h2 class="text-xl font-semibold mb-3">Ingredientes de la compra</h2>

                <div id="contenedorDetalles" class="space-y-4">

                    @php
                        $detallesIniciales = old('detalles', []);
                        if (count($detallesIniciales) === 0) {
                            $detallesIniciales = [
                                ['ingrediente_id' => '', 'cantidad' => '']
                            ];
                        }
                    @endphp

                    @foreach ($detallesIniciales as $i => $detalle)
                        <div class="detalle border rounded-lg p-4 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                                <div>
                                    <label class="block font-semibold mb-1">Ingrediente</label>
                                    <select name="detalles[{{ $i }}][ingrediente_id]"
                                        class="ingrediente w-full border rounded p-2" required>
                                        <option value="">Selecciona un ingrediente</option>
                                        @foreach ($ingredientes as $ingrediente)
                                            <option value="{{ $ingrediente->id }}"
                                                data-precio="{{ $ingrediente->precio }}"
                                                {{ ($detalle['ingrediente_id'] ?? '') == $ingrediente->id ? 'selected' : '' }}>
                                                {{ $ingrediente->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Cantidad</label>
                                    <input type="number"
                                        name="detalles[{{ $i }}][cantidad]"
                                        class="cantidad w-full border rounded p-2"
                                        min="0.01" step="0.01"
                                        value="{{ $detalle['cantidad'] ?? '' }}"
                                        required>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Precio unitario</label>
                                    <input type="text" class="precio w-full border rounded p-2 bg-gray-200"
                                        value="0.00" readonly>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1">Subtotal</label>
                                    <input type="text" class="subtotal w-full border rounded p-2 bg-gray-200"
                                        value="0.00" readonly>
                                </div>

                            </div>

                            <button type="button"
                                class="eliminar mt-3 bg-red-600 text-white px-3 py-1 rounded">
                                Eliminar ingrediente
                            </button>
                        </div>
                    @endforeach

                </div>

                <button type="button" id="agregar"
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
                    + Agregar ingrediente
                </button>

                <div class="mt-6 text-right">
                    <label class="text-xl font-bold">Total de la compra: $</label>
                    <span id="totalCompra" class="text-xl font-bold">0.00</span>
                </div>

                <div class="mt-6">
                    <label class="block font-semibold mb-1">Observaciones</label>
                    <textarea name="observaciones" rows="3"
                        class="w-full border rounded p-2">{{ old('observaciones') }}</textarea>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="bg-green-600 text-white px-5 py-2 rounded">
                        Guardar compra
                    </button>

                    <a href="{{ route('compras.index') }}"
                        class="bg-gray-500 text-white px-5 py-2 rounded">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>
    </div>

    <script>
        const contenedor = document.getElementById('contenedorDetalles');
        const botonAgregar = document.getElementById('agregar');
        const totalCompra = document.getElementById('totalCompra');

        const opcionesIngredientes = `
            <option value="">Selecciona un ingrediente</option>
            @foreach ($ingredientes as $ingrediente)
                <option value="{{ $ingrediente->id }}" data-precio="{{ $ingrediente->precio }}">
                    {{ $ingrediente->nombre }}
                </option>
            @endforeach
        `;

        function actualizarFilas() {
            const filas = contenedor.querySelectorAll('.detalle');

            filas.forEach((fila, indice) => {
                fila.querySelector('.ingrediente').name =
                    `detalles[${indice}][ingrediente_id]`;

                fila.querySelector('.cantidad').name =
                    `detalles[${indice}][cantidad]`;
            });

            calcularTotal();
        }

        function calcularTotal() {
            let total = 0;

            contenedor.querySelectorAll('.detalle').forEach(fila => {
                const select = fila.querySelector('.ingrediente');
                const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;

                const opcion = select.options[select.selectedIndex];
                const precio = parseFloat(opcion?.dataset.precio) || 0;

                const subtotal = cantidad * precio;

                fila.querySelector('.precio').value = precio.toFixed(2);
                fila.querySelector('.subtotal').value = subtotal.toFixed(2);

                total += subtotal;
            });

            totalCompra.textContent = total.toFixed(2);
        }

        function agregarFila() {
            const fila = document.createElement('div');
            fila.className = 'detalle border rounded-lg p-4 bg-gray-50';

            fila.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <div>
                        <label class="block font-semibold mb-1">Ingrediente</label>
                        <select class="ingrediente w-full border rounded p-2" required>
                            ${opcionesIngredientes}
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Cantidad</label>
                        <input type="number" class="cantidad w-full border rounded p-2"
                            min="0.01" step="0.01" required>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Precio unitario</label>
                        <input type="text" class="precio w-full border rounded p-2 bg-gray-200"
                            value="0.00" readonly>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Subtotal</label>
                        <input type="text" class="subtotal w-full border rounded p-2 bg-gray-200"
                            value="0.00" readonly>
                    </div>

                </div>

                <button type="button"
                    class="eliminar mt-3 bg-red-600 text-white px-3 py-1 rounded">
                    Eliminar ingrediente
                </button>
            `;

            contenedor.appendChild(fila);
            actualizarFilas();
        }

        botonAgregar.addEventListener('click', agregarFila);

        contenedor.addEventListener('input', function(evento) {
            if (evento.target.classList.contains('cantidad')) {
                calcularTotal();
            }
        });

        contenedor.addEventListener('change', function(evento) {
            if (evento.target.classList.contains('ingrediente')) {
                calcularTotal();
            }
        });

        contenedor.addEventListener('click', function(evento) {
            if (evento.target.classList.contains('eliminar')) {
                const filas = contenedor.querySelectorAll('.detalle');

                if (filas.length > 1) {
                    evento.target.closest('.detalle').remove();
                    actualizarFilas();
                } else {
                    alert('Debes conservar al menos un ingrediente.');
                }
            }
        });

        document.getElementById('formCompra').addEventListener('submit', function(evento) {
            const filas = contenedor.querySelectorAll('.detalle');
            const ingredientesSeleccionados = [];

            for (const fila of filas) {
                const ingrediente = fila.querySelector('.ingrediente').value;
                const cantidad = parseFloat(fila.querySelector('.cantidad').value);

                if (!ingrediente || !cantidad || cantidad <= 0) {
                    evento.preventDefault();
                    alert('Selecciona un ingrediente y escribe una cantidad válida en cada fila.');
                    return;
                }

                if (ingredientesSeleccionados.includes(ingrediente)) {
                    evento.preventDefault();
                    alert('No puedes agregar el mismo ingrediente más de una vez.');
                    return;
                }

                ingredientesSeleccionados.push(ingrediente);
            }
        });

        actualizarFilas();
    </script>

</body>
</html>
