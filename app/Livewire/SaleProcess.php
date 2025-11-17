<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Sale;
use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;

class SaleProcess extends Component
{
    // --- PASO 1: Selección de Cliente y Vehículo ---
    #[Rule('required|integer|exists:customers,id')]
    public $customer_id = '';
    
    #[Rule('required|integer|exists:vehicles,id')]
    public $vehicle_id = '';
    
    public $searchCustomer = '';
    public $searchVehicle = '';

    // --- PASO 2: Datos de la Venta (RF-19) ---
    #[Rule('required|numeric|min:0')]
    public $monto_total = '';
    
    #[Rule('required|string|in:Contado,Financiado')]
    public $metodo_pago = 'Contado';
    
    #[Rule('nullable|string')]
    public $observaciones = '';

    // --- PASO 3: Datos de Traslado (RF-26) ---
    #[Rule('boolean')]
    public $requires_shipment = false; // ¿La venta es fuera de Arequipa?

    #[Rule('required_if:requires_shipment,true|string|max:255')]
    public $ciudad_destino = '';
    
    #[Rule('required_if:requires_shipment,true|string|max:255')]
    public $direccion_entrega_completa = '';
    
    #[Rule('nullable|date')]
    public $fecha_estimada_entrega = '';

    /**
     * Cargar la lista de clientes (filtrable)
     */
    #[Computed]
    public function customers()
    {
        return Customer::query()
            ->where(function($query) {
                $query->where('nombres_completos', 'like', '%' . $this->searchCustomer . '%')
                      ->orWhere('numero_documento', 'like', '%' . $this->searchCustomer . '%');
            })
            ->orderBy('nombres_completos')
            ->get();
    }

    /**
     * Cargar la lista de vehículos (solo DISPONIBLES y filtrable)
     */
    #[Computed]
    public function vehicles()
    {
        return Vehicle::query()
            ->where('estado', 'Disponible') // RF-19: Solo vehículos disponibles
            ->where(function($query) {
                $query->where('marca', 'like', '%' . $this->searchVehicle . '%')
                      ->orWhere('modelo', 'like', '%' . $this->searchVehicle . '%')
                      ->orWhere('numero_chasis', 'like', '%' . $this->searchVehicle . '%');
            })
            ->orderBy('marca')
            ->get();
    }

    /**
     * Cuando se selecciona un vehículo, autocompletar el monto total
     */
    public function updatedVehicleId($value)
    {
        if ($value) {
            $vehicle = Vehicle::find($value);
            $this->monto_total = $vehicle ? $vehicle->precio_venta : '';
        } else {
            $this->monto_total = '';
        }
    }

    /**
     * Generar un número de boleta correlativo (RF-22)
     */
    private function getNextBoletaNumber()
    {
        $lastSale = Sale::orderBy('id', 'desc')->first();
        $nextNumber = $lastSale ? (int)substr($lastSale->numero_boleta, -8) + 1 : 1;
        return 'B001-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Guardar la venta (El corazón de la lógica)
     */
    public function saveSale()
    {
        $this->validate();

        // Usamos una transacción para asegurar que todo se guarde
        // o nada se guarde si hay un error.
        try {
            DB::beginTransaction();

            // 1. Determinar el estado final del vehículo
            $vehicleStatus = $this->requires_shipment ? 'En Traslado' : 'Vendido'; // RF-21 y RF-28

            // 2. Crear la Venta (RF-19)
            $sale = Sale::create([
                'user_id' => Auth::id(), // RF-20: Vendedor automático
                'customer_id' => $this->customer_id,
                'vehicle_id' => $this->vehicle_id,
                'numero_boleta' => $this->getNextBoletaNumber(), // RF-22
                'monto_total' => $this->monto_total,
                'metodo_pago' => $this->metodo_pago,
                'observaciones' => $this->observaciones,
            ]);

            // 3. Actualizar el estado del vehículo (RF-21 / RF-28)
            $vehicle = Vehicle::find($this->vehicle_id);
            $vehicle->estado = $vehicleStatus;
            $vehicle->save();

            // 4. Crear el Traslado si es necesario (RF-27)
            if ($this->requires_shipment) {
                Shipment::create([
                    'sale_id' => $sale->id,
                    'ciudad_destino' => $this->ciudad_destino,
                    'direccion_entrega_completa' => $this->direccion_entrega_completa,
                    'fecha_estimada_entrega' => $this->fecha_estimada_entrega,
                    'estado' => 'Pendiente', // Estado inicial
                ]);
            }

            // 5. Si todo salió bien, confirmamos la transacción
            DB::commit();

            // 6. Redirigir a una página de éxito (o al dashboard)
            session()->flash('success', '¡Venta registrada exitosamente! Boleta: ' . $sale->numero_boleta);
            return $this->redirect(route('dashboard'), navigate: true);

        } catch (\Exception $e) {
            // 7. Si algo falló, revertimos todo
            DB::rollBack();
            session()->flash('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sale-process');
    }
}