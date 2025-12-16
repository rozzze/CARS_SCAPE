<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Importaciones de componentes Livewire
use App\Livewire\VehicleInventory;
use App\Livewire\CustomerManagement;
use App\Livewire\SaleProcess;
use App\Livewire\ShipmentTracking;
use App\Livewire\UserManagement;
use App\Livewire\SaleHistory;

Route::get('/', function () {
    return view('welcome');
})->name('home');

use App\Http\Controllers\DashboardController;

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // --- RUTAS DEL SISTEMA ---
    
    // RF-09: Inventario (Jefe de Almacén y Vendedor)
    Route::get('inventario', VehicleInventory::class)
        ->middleware(['role:Jefe de Almacén|Vendedor'])
        ->name('inventario');

    // RF-15: Clientes (Vendedor)
    Route::get('clientes', CustomerManagement::class)
        ->middleware(['role:Vendedor'])
        ->name('clientes');

    // RF-19: Registrar Venta (Vendedor)
    Route::get('ventas/nueva', SaleProcess::class)
        ->middleware(['role:Vendedor'])
        ->name('ventas.crear');

    // RF-29: Traslados (Encargado de Traslado y Vendedor)
    Route::get('traslados', ShipmentTracking::class)
        ->middleware(['role:Encargado de Traslado|Vendedor'])
        ->name('traslados');

    // RF-04: Usuarios (Administrador)
    Route::get('usuarios', UserManagement::class)
        ->middleware(['role:Administrador'])
        ->name('usuarios');

    // Reportes (Administrador)
    Route::get('reportes', App\Livewire\Reports\SalesReports::class)
        ->middleware(['role:Administrador'])
        ->name('reportes');

    // RF-23: Historial de Ventas (Vendedor y Admin)
    Route::get('ventas', SaleHistory::class)
        ->middleware(['role:Vendedor|Administrador'])
        ->name('ventas.historial');
});