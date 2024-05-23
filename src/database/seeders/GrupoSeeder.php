<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoSeeder extends Seeder
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
        $materia = DB::table('materias')
            ->orderBy('id')
            ->first();
        DB::table('grupos')->insert([
            [
                'docente_id' => $docente->id,
                'materia_id' => $materia->id,
                'grupo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
