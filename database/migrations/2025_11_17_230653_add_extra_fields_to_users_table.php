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
        Schema::table('users', function (Blueprint $table) {
            // RF-04: Añadimos los campos que faltan
            $table->string('dni')->unique()->nullable()->after('email');
            $table->string('telefono')->nullable()->after('dni');
            $table->string('direccion')->nullable()->after('telefono');
            
            // RF-07: Para activar/desactivar usuarios
            $table->boolean('is_active')->default(true)->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dni', 'telefono', 'direccion', 'is_active']);
        });
    }
};