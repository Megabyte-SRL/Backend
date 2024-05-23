<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario_americo = DB::table('usuarios')
            ->orderBy('id')
            ->skip(1)
            ->first();
        $usuario_maria = DB::table('usuarios')
            ->orderBy('id')
            ->skip(2)
            ->first();
        $usuario_leticia = DB::table('usuarios')
            ->orderBy('id')
            ->skip(3)
            ->first();
        DB::table('docentes')->insert([
            [
                'usuario_id' => $usuario_americo->id,
                'nombre' => 'Americo',
                'apellido' => 'Fiorilo Lozada',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'usuario_id' => $usuario_maria->id,
                'nombre' => 'Maria Benita',
                'apellido' => 'Cespedes Guizada',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'usuario_id' => $usuario_leticia->id,
                'nombre' => 'Leticia',
                'apellido' => 'Blanco Coca',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
