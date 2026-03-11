<?php

namespace Database\Factories;

use App\Models\Lectura;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'autor' => $this->faker->name(),
            'anio' => $this->faker->numberBetween(1900, now()->year),
            'editorial' => $this->faker->company(),
            'paginas' => $this->faker->numberBetween(80,1200),
            'genero' => $this->faker->randomElement(['Fantasía','Ciencia ficción','Drama','Historia','Romance','Terror']),
            'portada' => null,
            'ubicacion_id' => $this->faker->numberBetween(1,3)
        ];
    }
}
