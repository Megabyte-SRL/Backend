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
                'nombre' => 'admin',
                'apellido' => 'admin',
                'email' => 'admin@fcyt.umss.edu',
                'password' => Hash::make('password1234'),
                'rol' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Jose Enrique',
                'apellido' => 'Camacho Silvestre',
                'email' => 'jose.camacho@gmail.com',
                'password' => Hash::make('password1234'),
                'rol' => 'docente',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
