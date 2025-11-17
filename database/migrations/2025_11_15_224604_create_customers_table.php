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
        Schema::create('customers', function (Blueprint $table) {

            $table->id();
            // Datos principales (RF-15)
            $table->string('tipo_documento'); // DNI/RUC
            $table->string('numero_documento')->unique(); // ->unique() para RF-16
            $table->string('nombres_completos'); // O Razón Social
            $table->string('direccion_completa');
            $table->string('ciudad');
            $table->string('telefono')->nullable();
            $table->string('correo_electronico')->nullable();

            $table->timestamps(); // Fecha de registro (created_at)
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
