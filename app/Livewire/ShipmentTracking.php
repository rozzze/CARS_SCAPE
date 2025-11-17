<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Sale;
use Livewire\WithPagination;
use Carbon\Carbon;
use Livewire\Attributes\Rule;

class ShipmentTracking extends Component
{
    use WithPagination;

    public $search = '';
    public $filter_estado = ''; // Filtro para Pendiente, En Tránsito, etc.

    // --- Propiedades para el Modal de VISTA (Tu Arquitectura) ---
    public $showViewModal = false;
    public $viewingShipmentId;
    public $viewingShipmentData = []; // Aquí guardaremos el array del traslado

    // --- Propiedades para el Modal de ACTUALIZACIÓN (RF-30) ---
    public $showUpdateModal = false;
    public $updatingShipmentId;
    public $currentStatus = '';
    public $nextStatus = '';
    
    #[Rule('nullable|string|max:500')]
    public $update_observaciones = '';

    /**
     * Cargar el traslado y sus relaciones en el array
     */
    public function openViewModal($shipmentId)
    {
        // Cargamos el traslado con todas sus relaciones anidadas
        $shipment = Shipment::with([
            'sale.customer', 
            'sale.vehicle', 
            'sale.user'
        ])->findOrFail($shipmentId);
        
        $this->viewingShipmentId = $shipment->id;
        $this->viewingShipmentData = $shipment->toArray(); // Convertimos a array
        
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingShipmentId = null;
        $this->viewingShipmentData = [];
    }

    /**
     * Abre el modal de confirmación para actualizar estado
     */
    public function openUpdateModal($shipmentId, $status)
    {
        $this->resetErrorBag();
        $this->updatingShipmentId = $shipmentId;
        $this->currentStatus = $status;
        $this->update_observaciones = '';

        // Determinamos cuál es el siguiente estado lógico
        if ($status === 'Pendiente') {
            $this->nextStatus = 'En Tránsito';
        } elseif ($status === 'En Tránsito') {
            $this->nextStatus = 'Entregado';
        }
        
        $this->showUpdateModal = true;
    }

    public function closeUpdateModal()
    {
        $this->showUpdateModal = false;
        $this->updatingShipmentId = null;
        $this->currentStatus = '';
        $this->nextStatus = '';
        $this->update_observaciones = '';
    }

    /**
     * Confirma y guarda la actualización del estado (RF-30, RF-31)
     */
    public function updateShipmentStatus()
    {
        $this->validate([
            'update_observaciones' => 'nullable|string|max:500'
        ]);

        if (!$this->updatingShipmentId || !$this->nextStatus) {
            return; // No hacer nada si no hay datos
        }

        $shipment = Shipment::findOrFail($this->updatingShipmentId);

        // Actualizamos el estado y la fecha correspondiente
        $shipment->estado = $this->nextStatus;
        
        if ($this->nextStatus === 'En Tránsito') {
            $shipment->fecha_salida = Carbon::now(); // Registra fecha/hora de salida
        } elseif ($this->nextStatus === 'Entregado') {
            $shipment->fecha_entrega = Carbon::now(); // Registra fecha/hora de entrega
        }

        // Añadimos observaciones si existen
        if ($this->update_observaciones) {
            $shipment->observaciones = ($shipment->observaciones ? $shipment->observaciones . "\n" : '') . 
                                       "[" . Carbon::now()->format('d/m/Y H:i') . " - " . $this->nextStatus . "]: " . 
                                       $this->update_observaciones;
        }

        $shipment->save();
        
        $this->closeUpdateModal();
        session()->flash('success', '¡Traslado actualizado a "' . $this->nextStatus . '" exitosamente!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $shipments = Shipment::query()
            // Cargamos relaciones para la tabla
            ->with(['sale.customer', 'sale.vehicle'])
            // --- ESTA ES LA LÓGICA CORREGIDA ---
            ->where(function($query) {
                // Buscamos por cliente o boleta
                $query->whereHas('sale.customer', function($q) {
                          $q->where('nombres_completos', 'like', '%' . $this->search . '%')
                            ->orWhere('numero_documento', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('sale', function($q) {
                          $q->where('numero_boleta', 'like', '%' . $this->search . '%');
                      });
            })
            // --- FIN DE LA LÓGICA CORREGIDA ---
            // Filtro de estado
            ->when($this->filter_estado, function ($query) {
                $query->where('estado', $this->filter_estado);
            })
            // Restricción de rol: Vendedor solo ve sus ventas
            ->when(auth()->user()->hasRole('Vendedor'), function($query) {
                $query->whereHas('sale', function($q) {
                    $q->where('user_id', auth()->id());
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.shipment-tracking', [
            'shipments' => $shipments,
        ]);
    }
}