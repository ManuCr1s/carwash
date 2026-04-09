<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'ADMINISTRADOR']);
        $cliente = Role::create(['name' => 'CLIENTE']);
        $usuario = Role::create(['name' => 'USUARIO']);

        Permission::create(['name' => 'crear reserva'])->assignRole($cliente);
        Permission::create(['name' => 'editar reserva'])->assignRole($cliente);
        Permission::create(['name' => 'eliminar reserva'])->assignRole($cliente);
        Permission::create(['name' => 'ver reserva'])->assignRole($cliente);

        Permission::create(['name' => 'crear usuario'])->assignRole($admin);
        Permission::create(['name' => 'editar usuario'])->assignRole($admin);
        Permission::create(['name' => 'eliminar usuario'])->assignRole($admin);
        Permission::create(['name' => 'ver usuario'])->assignRole($admin);

        Permission::create(['name' => 'ver pedido'])->assignRole($usuario);
        Permission::create(['name' => 'editar pedido'])->assignRole($usuario);
        Permission::create(['name' => 'ver reporte'])->assignRole($usuario); 
        Permission::create(['name' => 'ver despacho'])->assignRole($usuario); 
        Permission::create(['name' => 'ver vehiculo'])->assignRole($cliente);
    }
}
