<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Shipment;
use Faker\Factory as Faker;

class TestFlowSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('es_ES');
        
        // 1. Obtener Usuarios existentes (Vendedores)
        // Intentamos obtener usuarios que tengan rol de Vendedor.
        $users = User::role('Vendedor')->get();
        // Si no hay vendedores, usamos todos (fallback para evitar error si no corrieron el seeder de roles)
        if ($users->isEmpty()) {
            $this->command->warn('No users with "Vendedor" role found. Using all users.');
            $users = User::all();
        }
        
        if ($users->isEmpty()) {
            $this->command->warn('No Users found anywhere. Please seed users first. Skipping TestFlowSeeder.');
            return;
        }

        $this->command->info('Generando Vehículos...');

        // 2. Generar Vehículos (Algunos Vendidos, otros Disponibles)
        $vehicleStatuses = ['Disponible', 'Vendido'];
        $vehicles = [];

        // Generamos 60 vehículos para tener mas volumen
        for ($i = 0; $i < 60; $i++) {
            // 70% de probabilidad de estar vendido para generar bastantes ventas
            $estado = $faker->boolean(70) ? 'Vendido' : 'Disponible';
            
            $vehicles[] = Vehicle::create([
                'marca' => $faker->randomElement(['Toyota', 'Nissan', 'Hyundai', 'Kia', 'Chevrolet', 'Mazda', 'Ford', 'BMW', 'Mercedes', 'Audi']),
                'modelo' => $faker->word . ' ' . $faker->randomDigit(),
                'anio_fabricacion' => $faker->numberBetween(2019, 2025),
                'color' => $faker->safeColorName,
                'precio_venta' => $faker->randomFloat(2, 12000, 85000),
                'numero_motor' => strtoupper($faker->bothify('MOTOR-####??')),
                'numero_chasis' => strtoupper($faker->bothify('VIN-#######??')),
                'tipo_combustible' => $faker->randomElement(['Gasolina', 'Diesel', 'Híbrido', 'Eléctrico']),
                'transmision' => $faker->randomElement(['Manual', 'Automática', 'CVT']),
                'kilometraje' => $faker->numberBetween(0, 80000),
                'caracteristicas_adicionales' => $faker->sentence,
                'estado' => $estado,
            ]);
        }

        $this->command->info('Generando Clientes...');

        // 3. Generar Clientes (Aumentamos a 30)
        $customers = [];
        for ($i = 0; $i < 30; $i++) {
            $customers[] = Customer::create([
                'tipo_documento' => $faker->randomElement(['DNI', 'RUC', 'Pasaporte']),
                'numero_documento' => $faker->unique()->numerify('########'),
                'nombres_completos' => $faker->firstName . ' ' . $faker->lastName . ' ' . $faker->lastname,
                'direccion_completa' => $faker->address,
                'ciudad' => $faker->city,
                'telefono' => $faker->phoneNumber,
                'correo_electronico' => $faker->unique()->safeEmail,
            ]);
        }

        $this->command->info('Generando Ventas y Traslados...');

        // 4. Generar Ventas para los vehículos marcados como 'Vendido'
        foreach ($vehicles as $vehicle) {
            if ($vehicle->estado === 'Vendido') {
                $user = $users->random();
                $customer = $faker->randomElement($customers);
                
                // Fecha de venta aleatoria en el último mes para reportes
                $saleDate = $faker->dateTimeBetween('-2 months', 'now');

                $sale = Sale::create([
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'vehicle_id' => $vehicle->id,
                    'numero_boleta' => strtoupper($faker->bothify('BOL-#####')),
                    'monto_total' => $vehicle->precio_venta, // Asumimos precio full
                    'metodo_pago' => $faker->randomElement(['Efectivo', 'Tarjeta de Crédito', 'Transferencia']),
                    'observaciones' => $faker->sentence,
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);

                // 5. Generar Traslado (Shipment) opcionalmente (80% de probabilidad)
                if ($faker->boolean(80)) {
                    $shipmentDate = (clone $saleDate)->modify('+' . $faker->numberBetween(1, 5) . ' days');
                    
                    Shipment::create([
                        'sale_id' => $sale->id,
                        'ciudad_destino' => $customer->ciudad,
                        'direccion_entrega_completa' => $customer->direccion_completa,
                        'fecha_estimada_entrega' => (clone $shipmentDate)->modify('+3 days'),
                        'estado' => $faker->randomElement(['Pendiente', 'En Tránsito', 'Entregado']),
                        'fecha_salida' => $shipmentDate,
                        'fecha_entrega' => $faker->boolean(50) ? (clone $shipmentDate)->modify('+3 days') : null,
                        'observaciones' => $faker->sentence,
                        'created_at' => $shipmentDate,
                        'updated_at' => $shipmentDate,
                    ]);
                }
            }
        }

        $this->command->info('¡Datos de prueba generados exitosamente!');
    }
}
