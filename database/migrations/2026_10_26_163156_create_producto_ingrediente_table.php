<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('producto_ingrediente', function (Blueprint $table) {
        $table->id();

        $table->foreignId('producto_id')
            ->constrained('productos');

        $table->foreignId('ingrediente_id')
            ->constrained('ingredientes');

        $table->decimal('cantidad', 10, 2);

        $table->timestamps();

        $table->unique(['producto_id', 'ingrediente_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_ingrediente');
    }
};
