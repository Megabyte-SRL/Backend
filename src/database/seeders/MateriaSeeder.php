<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('materias')->insert([
            [
                'codigo' => 2010188,
                'nombre' => 'Servicios Telematicos',
                'nivel' => 'I',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
