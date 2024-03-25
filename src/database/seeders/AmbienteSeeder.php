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
                'aula' => 'A52',
                'capacidad' => 28,
                'accesibilidad' => true,
                'eliminado' => false,
            ],
            [
                'aula' => '691E',
                'capacidad' => 15,
                'accesibilidad' => true,
                'eliminado' => false,
            ]
        ]);
    }
}
