<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class VehicleInventory extends Component
{
    use WithPagination;

    public $search = '';

    //--- Propiedades para el modal de CREACIÓN ---
    public $showCreateModal = false;

    public $marca = '';
    public $modelo = '';
    public $anio_fabricacion = '';
    public $color = '';
    public $precio_venta = '';
    public $numero_motor = '';
    public $numero_chasis = '';
    public $tipo_combustible = '';
    public $transmision = '';
    public $kilometraje = 0;
    public $caracteristicas_adicionales = '';

    //--- Propiedades para el modal de EDICIÓN ---
    public $showEditModal = false;
    public $editingVehicleId; // 👈 CAMBIAMOS: Solo guardamos el ID
    public $editingVehicle = []; // 👈 NUEVO: Array para los datos del formulario

    /**
     * Resetea el formulario de creación
     */
    public function resetForm()
    {
        $this->reset(
            'marca', 'modelo', 'anio_fabricacion', 'color', 'precio_venta',
            'numero_motor', 'numero_chasis', 'tipo_combustible', 'transmision',
            'kilometraje', 'caracteristicas_adicionales'
        );
        $this->kilometraje = 0;
        $this->resetErrorBag();
    }

    /**
     * Abre el modal de creación
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    /**
     * Cierra el modal de creación
     */
    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    /**
     * Guarda el nuevo vehículo
     */
    public function saveVehicle()
    {
        $this->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'anio_fabricacion' => 'required|integer|digits:4|min:1950',
            'color' => 'required|string|max:255',
            'precio_venta' => 'required|numeric|min:0',
            'numero_motor' => 'required|string|max:255|unique:vehicles,numero_motor',
            'numero_chasis' => 'required|string|max:255|unique:vehicles,numero_chasis',
            'tipo_combustible' => 'required|string',
            'transmision' => 'required|string',
            'kilometraje' => 'required|integer|min:0',
            'caracteristicas_adicionales' => 'nullable|string',
        ]);

        Vehicle::create([
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'anio_fabricacion' => $this->anio_fabricacion,
            'color' => $this->color,
            'precio_venta' => $this->precio_venta,
            'numero_motor' => $this->numero_motor,
            'numero_chasis' => $this->numero_chasis,
            'tipo_combustible' => $this->tipo_combustible,
            'transmision' => $this->transmision,
            'kilometraje' => $this->kilometraje,
            'caracteristicas_adicionales' => $this->caracteristicas_adicionales,
            'estado' => 'Disponible'
        ]);

        $this->closeCreateModal();
        session()->flash('success', 'Vehículo registrado exitosamente.');
        $this->resetPage();
    }

    // ========================================
    // MÉTODOS DE EDICIÓN (CORREGIDOS) ✅
    // ========================================

    /**
     * Abre el modal de edición y carga los datos del vehículo
     */
    public function openEditModal($vehicleId)
    {
        $this->resetErrorBag();
        
        // 1. Buscamos el vehículo
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        // 2. Guardamos el ID
        $this->editingVehicleId = $vehicle->id;
        
        // 3. Cargamos los datos en el array
        $this->editingVehicle = [
            'marca' => $vehicle->marca,
            'modelo' => $vehicle->modelo,
            'anio_fabricacion' => $vehicle->anio_fabricacion,
            'color' => $vehicle->color,
            'precio_venta' => $vehicle->precio_venta,
            'numero_motor' => $vehicle->numero_motor,
            'numero_chasis' => $vehicle->numero_chasis,
            'tipo_combustible' => $vehicle->tipo_combustible,
            'transmision' => $vehicle->transmision,
            'kilometraje' => $vehicle->kilometraje,
            'caracteristicas_adicionales' => $vehicle->caracteristicas_adicionales,
        ];
        
        // 4. Abrimos el modal
        $this->showEditModal = true;
    }

    /**
     * Cierra el modal de edición
     */
    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingVehicleId = null;
        $this->editingVehicle = [];
    }

    /**
     * Actualiza el vehículo
     */
    public function updateVehicle()
    {
        // Validamos los datos
        $this->validate([
            'editingVehicle.marca' => 'required|string|max:255',
            'editingVehicle.modelo' => 'required|string|max:255',
            'editingVehicle.anio_fabricacion' => 'required|integer|digits:4|min:1950',
            'editingVehicle.color' => 'required|string|max:255',
            'editingVehicle.precio_venta' => 'required|numeric|min:0',
            'editingVehicle.numero_motor' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicles', 'numero_motor')->ignore($this->editingVehicleId),
            ],
            'editingVehicle.numero_chasis' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicles', 'numero_chasis')->ignore($this->editingVehicleId),
            ],
            'editingVehicle.tipo_combustible' => 'required|string',
            'editingVehicle.transmision' => 'required|string',
            'editingVehicle.kilometraje' => 'required|integer|min:0',
            'editingVehicle.caracteristicas_adicionales' => 'nullable|string',
        ]);

        // Buscamos y actualizamos el vehículo
        $vehicle = Vehicle::findOrFail($this->editingVehicleId);
        $vehicle->update($this->editingVehicle);

        // Cerramos y mostramos mensaje
        $this->closeEditModal();
        session()->flash('success', 'Vehículo actualizado exitosamente.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $vehicles = Vehicle::query()
            ->where(function($query) {
                $query->where('marca', 'like', '%' . $this->search . '%')
                      ->orWhere('modelo', 'like', '%' . $this->search . '%')
                      ->orWhere('numero_chasis', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.vehicle-inventory', [
            'vehicles' => $vehicles,
        ]);
    }
}