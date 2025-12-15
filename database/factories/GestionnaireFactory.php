<?php

namespace Database\Factories;

use App\Models\Centre;
use Illuminate\Database\Eloquent\Factories\Factory;

class GestionnaireFactory extends Factory
{
    public function definition(): array
    {
        return [
            'centre_id' => Centre::factory(),
            'association' => $this->faker->company . ' ' . $this->faker->randomElement(['Association', 'ONG', 'Fondation', 'Coopérative']),
            'recepisse_definitif' => 'REC-' . $this->faker->unique()->numberBetween(1000, 9999),
            'liste_membres' => $this->faker->randomElements(
                [$this->faker->name(), $this->faker->name(), $this->faker->name(), $this->faker->name()],
                $this->faker->numberBetween(2, 4)
            ),
            'liasse_fiscale' => 'LIA-' . $this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}