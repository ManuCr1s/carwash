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
        $admin = Role::create(['name' => 'administrador']);
        $cliente = Role::create(['name' => 'cliente']);
        $usuario = Role::create(['name' => 'usuario']);

        Permission::create(['name' => 'crear reserva'])->assignRole($cliente);
        Permission::create(['name' => 'editar reserva'])->assignRole($cliente);
        Permission::create(['name' => 'eliminar reserva'])->assignRole($cliente);
        Permission::create(['name' => 'ver reserva'])->assignRole($cliente);

        Permission::create(['name' => 'crear usuario'])->assignRole($admin);
        Permission::create(['name' => 'editar usuario'])->assignRole($admin);
        Permission::create(['name' => 'eliminar usuario'])->assignRole($admin);
        Permission::create(['name' => 'ver usuario'])->assignRole($admin);


    }
}
