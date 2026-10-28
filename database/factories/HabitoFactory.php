<?php

namespace Database\Factories;

use App\Models\Habito;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Habito>
 */
class HabitoFactory extends Factory
{
    protected $model = Habito::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Usuario_ID' => User::factory(),
            'Objetivo' => Str::limit(fake()->unique()->words(3, true), 45, ''),
            'Descripcion' => fake()->optional()->sentence(),
            'Prioridad' => fake()->randomElement(Habito::PRIORITIES),
            'Frecuencia' => fake()->randomElement(Habito::FREQUENCIES),
        ];
    }
}
