<?php

namespace Database\Factories;

use App\Models\Libro;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_libro' => Libro::factory(),
            'inicio_lectura' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'final_lectura' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'tiempo_lectura' => $this->faker->numberBetween(1, 60),
            'estado' => $this->faker->randomElement([
                        'Pendiente',
        'Leyendo',
        'Terminado',
        'Abandonado'
    ]),
            'puntuacion' => $this->faker->numberBetween(1, 5),
            'reseña' => $this->faker->optional()->paragraph(3),
        ];
    }
}
