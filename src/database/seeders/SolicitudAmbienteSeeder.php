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
        $usuario = DB::table('usuarios')->orderBy('id')->first();
        $horario_disponible = DB::table('horarios_disponibles')
            ->orderBy('id')
            ->first();
        DB::table('solicitudes_ambientes')->insert([
            [
                'usuario_id' => $usuario->id,
                'horario_disponible_id' => $horario_disponible->id,
                'capacidad' => 50,
                'materia' => 'Taller de redes avanzadas',
                'estado' => 'solicitado',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
