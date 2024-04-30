<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmbienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ambientes')->insert([
            [
                'nombre' => 'Auditorio',
                'capacidad' => 28,
                'descripcion' => 'Descripcion Auditorio.',
            ],
            [
                'nombre' => '692 F',
                'capacidad' => 15,
                'descripcion' => 'Descripcion 692 F.',
            ]
        ]);
    }
}
