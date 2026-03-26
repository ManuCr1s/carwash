<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol = Role::where('name','usuario')->first();
        /*
        $user = User::find(1);
        $user->assignRole(1);*/
        // Permission::create(['name' => 'ver pedido'])->assignRole($rol);
        // Permission::create(['name' => 'editar pedido'])->assignRole($rol);
        Permission::create(['name' => 'ver reporte'])->assignRole($rol);
    }
}
