<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar caché de permisos (buena práctica)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Crear Roles
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador']);
        $roleAlmacen = Role::firstOrCreate(['name' => 'Jefe de Almacén']);
        $roleVendedor = Role::firstOrCreate(['name' => 'Vendedor']);
        $roleTraslado = Role::firstOrCreate(['name' => 'Encargado de Traslado']);

        // 3. Crear Usuarios y Asignar Roles
        
        // Usuario Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador General',
                'password' => Hash::make('password') // 'password'
            ]
        );
        $admin->assignRole($roleAdmin);

        // Usuario Jefe de Almacén
        $almacen = User::firstOrCreate(
            ['email' => 'almacen@carsescape.com'],
            [
                'name' => 'Jefe de Almacén',
                'password' => Hash::make('password')
            ]
        );
        $almacen->assignRole($roleAlmacen);

        // Usuario Vendedor
        $vendedor = User::firstOrCreate(
            ['email' => 'vendedor@carsescape.com'],
            [
                'name' => 'Vendedor Sala',
                'password' => Hash::make('password')
            ]
        );
        $vendedor->assignRole($roleVendedor);

        // Usuario Encargado de Traslado
        $traslado = User::firstOrCreate(
            ['email' => 'traslado@carsescape.com'],
            [
                'name' => 'Encargado de Traslado',
                'password' => Hash::make('password')
            ]
        );
        $traslado->assignRole($roleTraslado);
    }
}