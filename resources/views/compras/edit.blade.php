<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Editar compra - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-4 md:p-8">

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-2">
        Editar compra #{{ $compra->id }}
    </h1>

    <p class="text-gray-600 mb-6">
        Modifica los datos de esta compra pendiente.
    </p>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('compras.update', $compra) }}"
          method="POST"
          class="space-y-6"
          id="formCompra">

        @csrf
        @method('PUT')

        <h2 class="text-xl font-semibold border-b pb-2">
            Datos generales
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label for="proveedor_id" class="block font-medium text-gray-700 mb-2">
                    Proveedor:
                </label>

                <select id="proveedor_id" name="proveedor_id" required
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white">
                    <option value="">Seleccione proveedor</option>

                    @foreach($proveedores as $proveedor)
                        <option value="{{ $proveedor->id }}"
                            @selected(old('proveedor_id', $compra->proveedor_id) == $proveedor->id)>
                            {{ $proveedor->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="user_id" class="block font-medium text-gray-700 mb-2">
                    Usuario responsable:
                </label>

                <select id="user_id" name="user_id" required
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white">

                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}"
                            @selected(old('user_id', $compra->user_id) == $usuario->id)>
                            {{ $usuario->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="fecha_compra" class="block font-medium text-gray-700 mb-2">
                    Fecha de compra:
                </label>

                <input type="date" id="fecha_compra" name="fecha_compra"
                    value="{{ old('fecha_compra', $compra->fecha_compra) }}"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

        </div>

        @php
            $detalles = old('detalles', $compra->detalles->map(fn($d) => [
                'ingrediente_id' => $d->ingrediente_id,
                'cantidad' => $d->cantidad,
            ])->toArray());
        @endphp

        <div>
            <h2 class="text-xl font-semibold border-b pb-2 mb-3">
                Ingredientes de la compra
            </h2>

            <p class="text-gray-600 text-sm mb-4">
                Agrega todos los ingredientes que necesites. El precio se obtiene automáticamente del catálogo.
            </p>

            <div id="contenedorIngredientes" class="space-y-4">

                @foreach($detalles as $i => $detalle)

                    <fieldset class="border border-gray-300 rounded-lg p-4 fila-ingrediente">

                        <div class="flex justify-between items-center mb-3">
                            <legend class="font-semibold text-gray-700">
                                Ingrediente {{ $i + 1 }}
                            </legend>

                            <button type="button"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded eliminar">
                                Eliminar
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                            <div>
                                <label class="block font-medium text-gray-700 mb-2">
                                    Ingrediente:
                                </label>

                                <select name="detalles[{{ $i }}][ingrediente_id]"
                                    class="ingrediente-select w-full border border-gray-300 rounded px-3 py-2 bg-white"
                                    required>

                                    <option value="">Seleccione ingrediente</option>

                                    @foreach($ingredientes as $ingrediente)
                                        <option value="{{ $ingrediente->id }}"
                                            data-precio="{{ $ingrediente->precio }}"
                                            @selected(($detalle['ingrediente_id'] ?? '') == $ingrediente->id)>
                                            {{ $ingrediente->nombre }} ({{ $ingrediente->unidad_medida }})
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div>
                                <label class="block font-medium text-gray-700 mb-2">
                                    Cantidad:
                                </label>

                                <input type="number"
                                    name="detalles[{{ $i }}][cantidad]"
                                    min="0.01"
                                    step="0.01"
                                    value="{{ $detalle['cantidad'] ?? '' }}"
                                    class="cantidad w-full border border-gray-300 rounded px-3 py-2"
                                    required>
                            </div>

                            <div>
                                <label class="block font-medium text-gray-700 mb-2">
                                    Precio unitario:
                                </label>

                                <input type="text"
                                    class="precio w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
                                    readonly>
                            </div>

                            <div>
                                <label class="block font-medium text-gray-700 mb-2">
                                    Subtotal:
                                </label>

                                <input type="text"
                                    class="subtotal w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
                                    readonly>
                            </div>

                        </div>

                    </fieldset>

                @endforeach

            </div>

            <button type="button" id="agregarIngrediente"
                class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Agregar ingrediente
            </button>

            <div class="mt-6 text-right">
                <p class="text-xl font-bold">
                    Total de compra:
                    <span id="totalCompra">$0.00</span>
                </p>
            </div>
        </div>

        <div>
            <label for="observaciones" class="block font-medium text-gray-700 mb-2">
                Observaciones:
            </label>

            <textarea id="observaciones" name="observaciones" rows="3"
                class="w-full border border-gray-300 rounded px-3 py-2">{{ old('observaciones', $compra->observaciones) }}</textarea>
        </div>

        <div class="flex flex-wrap gap-3 pt-4 border-t">

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Guardar cambios
            </button>

            <a href="{{ route('compras.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Cancelar
            </a>

        </div>

    </form>
</div>

<script>
    const contenedor = document.getElementById('contenedorIngredientes');
    const botonAgregar = document.getElementById('agregarIngrediente');
    const totalCompra = document.getElementById('totalCompra');

    const opcionesIngredientes = `
        <option value="">Seleccione ingrediente</option>
        @foreach($ingredientes as $ingrediente)
            <option value="{{ $ingrediente->id }}"
                data-precio="{{ $ingrediente->precio }}">
                {{ $ingrediente->nombre }} ({{ $ingrediente->unidad_medida }})
            </option>
        @endforeach
    `;

    function actualizarTotal() {
        let total = 0;

        contenedor.querySelectorAll('.fila-ingrediente').forEach(fila => {
            const select = fila.querySelector('.ingrediente-select');
            const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;

            const opcion = select.options[select.selectedIndex];
            const precio = parseFloat(opcion?.dataset.precio) || 0;

            const subtotal = cantidad * precio;

            fila.querySelector('.precio').value = '$' + precio.toFixed(2);
            fila.querySelector('.subtotal').value = '$' + subtotal.toFixed(2);

            total += subtotal;
        });

        totalCompra.textContent = '$' + total.toFixed(2);
    }

    function actualizarFilas() {
        const filas = contenedor.querySelectorAll('.fila-ingrediente');

        filas.forEach((fila, index) => {
            fila.querySelector('legend').textContent = 'Ingrediente ' + (index + 1);

            fila.querySelector('.ingrediente-select').name =
                `detalles[${index}][ingrediente_id]`;

            fila.querySelector('.cantidad').name =
                `detalles[${index}][cantidad]`;
        });

        actualizarTotal();
    }

    function agregarFila() {
        const fila = document.createElement('fieldset');

        fila.className = 'border border-gray-300 rounded-lg p-4 fila-ingrediente';

        fila.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <legend class="font-semibold text-gray-700"></legend>

                <button type="button"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded eliminar">
                    Eliminar
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        Ingrediente:
                    </label>

                    <select class="ingrediente-select w-full border border-gray-300 rounded px-3 py-2 bg-white"
                        required>
                        ${opcionesIngredientes}
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        Cantidad:
                    </label>

                    <input type="number" min="0.01" step="0.01"
                        class="cantidad w-full border border-gray-300 rounded px-3 py-2"
                        required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        Precio unitario:
                    </label>

                    <input type="text"
                        class="precio w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
                        readonly>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-2">
                        Subtotal:
                    </label>

                    <input type="text"
                        class="subtotal w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
                        readonly>
                </div>

            </div>
        `;

        contenedor.appendChild(fila);
        actualizarFilas();
    }

    botonAgregar.addEventListener('click', agregarFila);

    contenedor.addEventListener('click', function(event) {
        if (event.target.classList.contains('eliminar')) {
            event.target.closest('.fila-ingrediente').remove();
            actualizarFilas();
        }
    });

    contenedor.addEventListener('input', actualizarTotal);
    contenedor.addEventListener('change', actualizarTotal);

    actualizarFilas();

    document.getElementById('formCompra').addEventListener('submit', function(event) {
        const filas = contenedor.querySelectorAll('.fila-ingrediente');

        if (filas.length === 0) {
            event.preventDefault();
            alert('Debes agregar por lo menos un ingrediente.');
            return;
        }

        const seleccionados = [];

        for (const fila of filas) {
            const ingrediente = fila.querySelector('.ingrediente-select').value;

            if (seleccionados.includes(ingrediente)) {
                event.preventDefault();
                alert('No puedes agregar el mismo ingrediente más de una vez.');
                return;
            }

            seleccionados.push(ingrediente);
        }
    });
</script>

</body>
</html>
