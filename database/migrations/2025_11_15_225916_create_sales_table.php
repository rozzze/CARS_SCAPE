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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Conexiones (Llaves Foráneas)
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('vehicle_id')->unique()->constrained('vehicles');

            $table->string('numero_boleta')->unique();
            $table->decimal('monto_total', 10, 2);
            $table->string('metodo_pago');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
