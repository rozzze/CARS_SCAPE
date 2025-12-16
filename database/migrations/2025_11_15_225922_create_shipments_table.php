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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            // Conexión (Llave Foránea)
            $table->foreignId('sale_id')->constrained('sales');

            $table->string('ciudad_destino');
            $table->string('direccion_entrega_completa');
            $table->date('fecha_estimada_entrega')->nullable();
            $table->string('estado')->default('Pendiente');

            $table->timestamp('fecha_salida')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
