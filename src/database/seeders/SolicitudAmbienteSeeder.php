<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SolicitudAmbienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $docente = DB::table('docentes')
            ->orderBy('id')
            ->first();
        $horario_disponible = DB::table('horarios_disponibles')
            ->orderBy('id')
            ->first();
        $grupo = DB::table('grupos')
            ->orderBy('id')
            ->first();
        DB::table('solicitudes_ambientes')->insert([
            [
                'docente_id' => $docente->id,
                'horario_disponible_id' => $horario_disponible->id,
                'grupo_id' => $grupo->id,
                'capacidad' => 50,
                'estado' => 'solicitado',
                'tipo_reserva' => 'Examen Mesa',
                'prioridad' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
