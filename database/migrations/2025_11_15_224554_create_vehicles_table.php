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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Datos principales (RF-09)
            $table->string('marca');
            $table->string('modelo');
            $table->year('anio_fabricacion'); // 'year' es un tipo de dato específico
            $table->string('color');
            $table->decimal('precio_venta', 10, 2); // 10 dígitos totales, 2 decimales
            $table->string('numero_motor')->unique();  // ->unique() para RF-10
            $table->string('numero_chasis')->unique(); // ->unique() para RF-10
            $table->string('tipo_combustible');
            $table->string('transmision'); // Manual/Automática
            $table->integer('kilometraje')->default(0);
            $table->text('caracteristicas_adicionales')->nullable();

            // Control del sistema
            $table->string('estado')->default('Disponible'); // Disponible, Vendido, En Traslado

            $table->timestamps(); // Fecha de ingreso (created_at) y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
