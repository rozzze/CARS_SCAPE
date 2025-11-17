<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Livewire\WithPagination;

class SaleHistory extends Component
{
    use WithPagination;

    public $search = '';

    // --- Propiedades para el Modal de VISTA (Tu Arquitectura) ---
    public $showViewModal = false;
    public $viewingSaleId;
    public $viewingSaleData = []; // Aquí guardaremos el array de la venta

    /**
     * Cargar la venta y sus relaciones en el array
     */
    public function openViewModal($saleId)
    {
        $sale = Sale::with(['customer', 'vehicle', 'user', 'shipment'])
                    ->findOrFail($saleId);
        
        $this->viewingSaleId = $sale->id;
        $this->viewingSaleData = $sale->toArray(); // Convertimos el modelo y sus relaciones a un array
        
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewingSaleId = null;
        $this->viewingSaleData = [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sales = Sale::query()
            // Cargamos las relaciones para evitar N+1 queries en la tabla
            ->with(['customer', 'vehicle', 'user', 'shipment']) 
            ->where(function($query) {
                // Buscamos por N° Boleta
                $query->where('numero_boleta', 'like', '%' . $this->search . '%')
                      // Buscamos en la relación de Cliente
                      ->orWhereHas('customer', function($q) {
                          $q->where('nombres_completos', 'like', '%' . $this->search . '%')
                            ->orWhere('numero_documento', 'like', '%' . $this->search . '%');
                      })
                      // Buscamos en la relación de Vehículo
                      ->orWhereHas('vehicle', function($q) {
                          $q->where('marca', 'like', '%' . $this->search . '%')
                            ->orWhere('modelo', 'like', '%' . $this->search . '%')
                            ->orWhere('numero_chasis', 'like', '%' . $this->search . '%');
                      });
            })
            // Solo mostramos las ventas del Vendedor actual (si no es Admin)
            ->when(auth()->user()->hasRole('Vendedor'), function($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.sale-history', [
            'sales' => $sales,
        ]);
    }
}