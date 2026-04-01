<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            ['id' => 1, 'name' => 'Lavado Express', 'description' => 'Lavado exterior, silicona de llantas', 'price' => 10.00, 'group_id' => 1],
            ['id' => 2, 'name' => 'Lavado Standard', 'description' => 'Lavado express, limpieza Interior, Aspirado, Silicona Tableros', 'price' => 15.00, 'group_id' =>1],
            ['id' => 3, 'name' => 'Lavado Deluxe', 'description' => 'Lavado standard, encerado', 'price' => 35.00, 'group_id' =>1],
            ['id' => 4, 'name' => 'Lavado Ahorasi', 'description' => 'Lavado Deluxe, lavado de techo, lavado de motor', 'price' => 55.00, 'group_id' =>1],
            ['id' => 5, 'name' => 'Lavado Salon Standard', 'description' => 'Lavado Deluxe, puertas, cinturones, aros, alfombras, pisos, detallado interior', 'price' => 159.00, 'group_id' => 2], 
            ['id' => 6, 'name' => 'Lavado Salon Ahorasi', 'description' => 'Lavado Salon Standard, retirado de asientos','price' => 229.00, 'group_id' => 2],
        ]);
    }
}
