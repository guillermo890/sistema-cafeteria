
<nav class="bg-gray-900 text-white p-4 rounded-lg shadow mb-6">

    <div class="flex flex-wrap items-center justify-between gap-4">

        <a href="{{ route('ventas.index') }}"
           class="text-xl font-bold text-yellow-400">
            CAFETERÍA
        </a>

        <div class="flex flex-wrap gap-2">

            <a href="{{ route('roles.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Roles
            </a>

            <a href="{{ route('usuarios.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Usuarios
            </a>

            <a href="{{ route('clientes.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Clientes
            </a>

            <a href="{{ route('categorias.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Categorías
            </a>

            <a href="{{ route('productos.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Productos
            </a>

            <a href="{{ route('ingredientes.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Ingredientes
            </a>

            <a href="{{ route('proveedores.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Proveedores
            </a>

            <a href="{{ route('compras.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Compras
            </a>

            <a href="{{ route('metodos.index') }}"
               class="px-3 py-2 rounded hover:bg-gray-700">
                Métodos de pago
            </a>

            <a href="{{ route('ventas.index') }}"
               class="px-3 py-2 rounded bg-blue-600 hover:bg-blue-700">
                Ventas
            </a>

        </div>

    </div>

</nav>
