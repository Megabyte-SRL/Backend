<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionAmbienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ambiente = DB::table('ambientes')->orderBy('id')->first();
        DB::table('ubicaciones')->insert([
            [
                'ambiente_id' => $ambiente->id,
                'lugar' => 'Descripcion ubicacion ambiente 1',
                'edificio' => 'Edificio multiacademico.',
                'piso' => 4,
            ]
        ]);
    }
}
