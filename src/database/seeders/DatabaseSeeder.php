<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AmbienteSeeder::class);
        $this->call(HorarioDisponibleSeeder::class);
        $this->call(UbicacionAmbienteSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(SolicitudAmbienteSeeder::class);
    }
}
