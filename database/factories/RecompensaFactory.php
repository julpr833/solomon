<?php

namespace Database\Factories;

use App\Models\Recompensa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Recompensa>
 */
class RecompensaFactory extends Factory
{
    protected $model = Recompensa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Usuario_ID' => User::factory(),
            'Nombre' => Str::limit(fake()->unique()->words(2, true), 45, ''),
            'Descripcion' => fake()->optional()->sentence(),
            'Emoji' => '🏆',
        ];
    }
}
