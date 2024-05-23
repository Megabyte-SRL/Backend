<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            [
                'email' => 'admin@fcyt.umss.edu',
                'password' => Hash::make('password1234'),
                'rol' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'americo.fiorilo@fcyt.umss.edu',
                'password' => Hash::make('password1234'),
                'rol' => 'docente',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'maria.cespedes@fcyt.umss.edu',
                'password' => Hash::make('password1234'),
                'rol' => 'docente',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'email' => 'leticia.blanco@fcyt.umss.edu',
                'password' => Hash::make('password1234'),
                'rol' => 'docente',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
