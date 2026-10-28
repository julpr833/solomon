<?php

namespace Database\Factories;

use App\Models\Habito;
use App\Models\Meta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meta>
 */
class MetaFactory extends Factory
{
    protected $model = Meta::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Habito_ID' => Habito::factory(),
            'RachaRequerida' => fake()->unique()->numberBetween(1, 365),
            'Repetible' => true,
        ];
    }
}
