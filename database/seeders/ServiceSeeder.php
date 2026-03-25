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
            ['id' => 1, 'name' => 'Lavado Basico', 'description' => 'Solo lavado', 'price' => 10.00],
            ['id' => 2, 'name' => 'Lavado Completo', 'description' => 'Lavado y Planchado', 'price' => 20.00 ],
            ['id' => 3, 'name' => 'Lavado de Salon', 'description' => 'Lavado y Planchado y serviciio de bebidas','price' => 50.00 ],
        ]);
    }
}
