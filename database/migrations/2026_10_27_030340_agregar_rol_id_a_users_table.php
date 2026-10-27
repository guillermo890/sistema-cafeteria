
<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // La columna rol_id ya existe en la migración create_users_table.
    }

    public function down(): void
    {
        // No eliminar rol_id: pertenece a create_users_table.
    }
};
