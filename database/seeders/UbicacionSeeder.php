<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Ubicacion::create([
            'user_id' => 1,
            'nombre' => 'Sin Ubicación'
        ]);

        \App\Models\Ubicacion::create([
            'user_id' => 1,
            'nombre' => 'Salon'
        ]);

        \App\Models\Ubicacion::create([
            'user_id' => 1,
            'nombre' => 'Biblioteca'
        ]);

        \App\Models\Ubicacion::create([
            'user_id' => 1,
            'nombre' => 'Estudio'
        ]);
    }
}
