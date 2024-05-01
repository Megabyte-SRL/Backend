<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioDisponibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ambiente = DB::table('ambientes')->orderBy('id')->first();
        DB::table('horarios_disponibles')->insert([
            [
                'ambiente_id' => $ambiente->id,
                'fecha' => Carbon::parse('2023-01-01'),
                'hora_inicio' => '08:00:00',
                'hora_fin' => '10:00:00',
            ]
        ]);
    }
}
