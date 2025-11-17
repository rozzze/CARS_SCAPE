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
            $table->foreignId('user_id')->constrained('users'); // El Vendedor
            $table->foreignId('customer_id')->constrained('customers'); // El Cliente
            $table->foreignId('vehicle_id')->unique()->constrained('vehicles'); // El Vehículo (único, solo se vende 1 vez)

            // Datos de la Venta (RF-19)
            $table->string('numero_boleta')->unique(); // RF-22 (Ej: B001-00000001)
            $table->decimal('monto_total', 10, 2);
            $table->string('metodo_pago'); // Contado, Financiado
            $table->text('observaciones')->nullable();

            // created_at servirá como la 'fecha_venta' (RF-19)
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
