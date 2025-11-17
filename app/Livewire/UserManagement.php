<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    // --- Propiedades para CREACIÓN (Primitivos) ---
    public $showCreateModal = false;
    public $name = '';
    public $email = '';
    public $dni = '';
    public $telefono = '';
    public $direccion = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = '';
    public $is_active = true;

    // --- Propiedades para EDICIÓN (Array + ID) ---
    public $showEditModal = false;
    public $editingUserId;
    public $editingUser = [
        'name' => '',
        'email' => '',
        'dni' => '',
        'telefono' => '',
        'direccion' => '',
        'password' => '',
        'password_confirmation' => '',
        'role_id' => '',
        'is_active' => true,
    ]; // Inicializamos con claves

    /**
     * Resetea el formulario de creación
     */
    public function resetCreateForm()
    {
        $this->reset(
            'name', 'email', 'dni', 'telefono', 'direccion', 
            'password', 'password_confirmation', 'role_id', 'is_active'
        );
        $this->is_active = true; // Valor por defecto
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

    public function saveUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'dni' => 'nullable|string|max:10|unique:users,dni',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active,
        ]);

        $user->assignRole($this->role_id);

        $this->closeCreateModal();
        session()->flash('success', 'Usuario creado exitosamente.');
        $this->resetPage();
    }

    // --- LÓGICA DE EDICIÓN (Tu Arquitectura) ---

    public function openEditModal($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $this->editingUserId = $user->id;
        
        $this->editingUser = [
            'name' => $user->name,
            'email' => $user->email,
            'dni' => $user->dni,
            'telefono' => $user->telefono,
            'direccion' => $user->direccion,
            'role_id' => $user->roles->first()->id ?? null,
            'is_active' => $user->is_active,
            'password' => '', // Siempre vacío al abrir
            'password_confirmation' => '', // Siempre vacío al abrir
        ];

        $this->resetErrorBag();
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingUserId = null;
    }

    public function updateUser()
    {
        // Validamos usando la notación de punto
        $this->validate([
            'editingUser.name' => 'required|string|max:255',
            'editingUser.email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUserId),
            ],
            'editingUser.dni' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('users', 'dni')->ignore($this->editingUserId),
            ],
            'editingUser.telefono' => 'nullable|string|max:15',
            'editingUser.direccion' => 'nullable|string|max:255',
            'editingUser.password' => 'nullable|string|min:8|confirmed', // Nullable (opcional)
            'editingUser.role_id' => 'required|integer|exists:roles,id',
            'editingUser.is_active' => 'required|boolean',
        ]);

        $user = User::findOrFail($this->editingUserId);
        
        // Preparamos el array de datos
        $data = $this->editingUser;

        // Si la contraseña está vacía, la quitamos del array
        // para no sobreescribir la existente con null
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            // Si hay contraseña, la hasheamos
            $data['password'] = Hash::make($data['password']);
        }
        
        // Quitamos la confirmación (no existe en BBDD)
        unset($data['password_confirmation']);
        // Quitamos el role_id (se maneja por separado)
        $roleId = $data['role_id'];
        unset($data['role_id']);

        // Actualizamos el usuario
        $user->update($data);

        // Sincronizamos el rol
        $user->syncRoles([$roleId]);

        $this->closeEditModal();
        session()->flash('success', 'Usuario actualizado exitosamente.');
    }
    
    // --- RENDER ---

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->with('roles') // Cargamos la relación de roles
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('dni', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10);
            
        $roles = Role::all(); // Obtenemos todos los roles para los modales

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles, // Pasamos los roles a la vista
        ]);
    }
}