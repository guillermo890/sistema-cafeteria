
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Registrar compra - Cafetería</title>
</head>

<body class="bg-gray-100 min-h-screen p-4 md:p-8">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">

        <h1 class="text-2xl font-bold mb-6">
            Registrar nueva compra
        </h1>

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('compras.store') }}"
              method="POST"
              class="space-y-6">

            @csrf

            {{-- Datos generales --}}
            <h2 class="text-xl font-semibold border-b pb-2">
                Datos generales
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label for="proveedor_id"
                           class="block font-medium text-gray-700 mb-2">
                        Proveedor:
                    </label>

                    <select
                        id="proveedor_id"
                        name="proveedor_id"
                        required
                        class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">Seleccione un proveedor</option>

                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}"
                                @selected(old('proveedor_id') == $proveedor->id)>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="user_id"
                           class="block font-medium text-gray-700 mb-2">
                        Usuario responsable:
                    </label>

                    <select
                        id="user_id"
                        name="user_id"
                        required
                        class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <option value="">Seleccione un usuario</option>

                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}"
                                @selected(old('user_id') == $usuario->id)>
                                {{ $usuario->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="fecha_compra"
                           class="block font-medium text-gray-700 mb-2">
                        Fecha de compra:
                    </label>

                    <input
                        type="date"
                        id="fecha_compra"
                        name="fecha_compra"
                        value="{{ old('fecha_compra', date('Y-m-d')) }}"
                        required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            {{-- Ingredientes --}}
            <div>
                <h2 class="text-xl font-semibold border-b pb-2 mb-3">
                    Ingredientes de la compra
                </h2>

                <p class="text-gray-600 text-sm mb-4">
                    Selecciona hasta tres ingredientes diferentes.
                    El primer ingrediente es obligatorio.
                </p>

                <div class="space-y-4">

                    @for($i = 0; $i < 3; $i++)

                        <fieldset class="border border-gray-300 rounded-lg p-4">

                            <legend class="px-2 font-semibold text-gray-700">
                                Ingrediente {{ $i + 1 }}
                                @if($i === 0)
                                    <span class="text-red-600">*</span>
                                @else
                                    <span class="text-gray-500 text-sm font-normal">
                                        (Opcional)
                                    </span>
                                @endif
                            </legend>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div>
                                    <label for="ingrediente_{{ $i }}"
                                           class="block font-medium text-gray-700 mb-2">
                                        Ingrediente:
                                    </label>

                                    <select
                                        id="ingrediente_{{ $i }}"
                                        name="detalles[{{ $i }}][ingrediente_id]"
                                        @if($i === 0) required @endif
                                        class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">

                                        <option value="">Seleccione ingrediente</option>

                                        @foreach($ingredientes as $ingrediente)
                                            <option value="{{ $ingrediente->id }}"
                                                @selected(old("detalles.$i.ingrediente_id") == $ingrediente->id)>
                                                {{ $ingrediente->nombre }}
                                                ({{ $ingrediente->unidad_medida }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="cantidad_{{ $i }}"
                                           class="block font-medium text-gray-700 mb-2">
                                        Cantidad:
                                    </label>

                                    <input
                                        type="number"
                                        id="cantidad_{{ $i }}"
                                        name="detalles[{{ $i }}][cantidad]"
                                        min="0.01"
                                        step="0.01"
                                        value="{{ old("detalles.$i.cantidad") }}"
                                        @if($i === 0) required @endif
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="precio_{{ $i }}"
                                           class="block font-medium text-gray-700 mb-2">
                                        Precio unitario:
                                    </label>

                                    <input
                                        type="number"
                                        id="precio_{{ $i }}"
                                        name="detalles[{{ $i }}][precio_unitario]"
                                        min="0"
                                        step="0.01"
                                        value="{{ old("detalles.$i.precio_unitario") }}"
                                        @if($i === 0) required @endif
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                            </div>

                        </fieldset>

                    @endfor

                </div>
            </div>

            {{-- Observaciones --}}
            <div>
                <label for="observaciones"
                       class="block font-medium text-gray-700 mb-2">
                    Observaciones:
                </label>

                <textarea
                    id="observaciones"
                    name="observaciones"
                    rows="3"
                    placeholder="Escribe alguna observación sobre la compra (opcional)"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observaciones') }}</textarea>
            </div>

            {{-- Botones --}}
            <div class="flex flex-wrap gap-3 pt-4 border-t">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar compra
                </button>

                <a href="{{ route('compras.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</body>
</html>
