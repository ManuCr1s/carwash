<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('states')->insert([
            ['id' => 1, 'name' => 'Pendiente', 'description' => 'Inicio de Lavado'],
            ['id' => 2, 'name' => 'Confirmado', 'description' => 'En proceso de Lavado'],
            ['id' => 3, 'name' => 'Atendido', 'description' => 'Final de Lavado'],
        ]);
    }
}
