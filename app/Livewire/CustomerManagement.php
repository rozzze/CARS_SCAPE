<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class CustomerManagement extends Component
{
    use WithPagination;

    public $search = '';

    //Propiedades para CREACIÓN
    public $showCreateModal = false;
    public $tipo_documento = 'DNI';
    public $numero_documento = '';
    public $nombres_completos = '';
    public $direccion_completa = '';
    public $ciudad = '';
    public $telefono = '';
    public $correo_electronico = '';

    //Propiedades para EDICIÓN
    public $showEditModal = false;
    public $editingCustomerId;
    public $editingCustomer = [
        'tipo_documento' => 'DNI',
        'numero_documento' => '',
        'nombres_completos' => '',
        'direccion_completa' => '',
        'ciudad' => '',
        'telefono' => '',
        'correo_electronico' => '',
    ];

    /**
     * Resetea el formulario de creación
     */
    public function resetCreateForm()
    {
        $this->reset(
            'tipo_documento', 'numero_documento', 'nombres_completos', 
            'direccion_completa', 'ciudad', 'telefono', 'correo_electronico'
        );
        $this->tipo_documento = 'DNI'; // Valor por defecto
        $this->resetErrorBag();
    }

    // --- LÓGICA DE CREACIÓN ---

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function saveCustomer()
    {
        $this->validate([
            'tipo_documento' => 'required|string|in:DNI,RUC',
            'numero_documento' => 'required|string|max:15|unique:customers,numero_documento', // RF-16
            'nombres_completos' => 'required|string|max:255',
            'direccion_completa' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:15',
            'correo_electronico' => 'nullable|email|max:255',
        ]);

        Customer::create([
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'nombres_completos' => $this->nombres_completos,
            'direccion_completa' => $this->direccion_completa,
            'ciudad' => $this->ciudad,
            'telefono' => $this->telefono,
            'correo_electronico' => $this->correo_electronico,
        ]);

        $this->closeCreateModal();
        session()->flash('success', 'Cliente registrado exitosamente.');
        $this->resetPage();
    }

    // --- LÓGICA DE EDICIÓN ---

    public function openEditModal($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $this->editingCustomerId = $customer->id;
        
        $this->editingCustomer = [
            'tipo_documento' => $customer->tipo_documento,
            'numero_documento' => $customer->numero_documento,
            'nombres_completos' => $customer->nombres_completos,
            'direccion_completa' => $customer->direccion_completa,
            'ciudad' => $customer->ciudad,
            'telefono' => $customer->telefono,
            'correo_electronico' => $customer->correo_electronico,
        ];

        $this->resetErrorBag();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingCustomerId = null;
    }

    public function updateCustomer()
    {
        $this->validate([
            'editingCustomer.tipo_documento' => 'required|string|in:DNI,RUC',
            'editingCustomer.numero_documento' => [
                'required',
                'string',
                'max:15',
                Rule::unique('customers', 'numero_documento')->ignore($this->editingCustomerId), // RF-16 (Edit)
            ],
            'editingCustomer.nombres_completos' => 'required|string|max:255',
            'editingCustomer.direccion_completa' => 'required|string|max:255',
            'editingCustomer.ciudad' => 'required|string|max:100',
            'editingCustomer.telefono' => 'nullable|string|max:15',
            'editingCustomer.correo_electronico' => 'nullable|email|max:255',
        ]);

        $customer = Customer::findOrFail($this->editingCustomerId);
        $customer->update($this->editingCustomer);

        $this->closeEditModal();
        session()->flash('success', 'Cliente actualizado exitosamente.');
    }

    // --- RENDER ---

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::query()
            ->where(function($query) {
                $query->where('nombres_completos', 'like', '%' . $this->search . '%')
                      ->orWhere('numero_documento', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.customer-management', [
            'customers' => $customers,
        ]);
    }
}