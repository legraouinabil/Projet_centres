<?php

namespace Database\Factories;

use App\Models\Impact;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartenaireFactory extends Factory
{
    public function definition(): array
    {
        return [
            'impact_id' => Impact::factory(),
            'nom' => $this->faker->company,
            'evenements_organises' => $this->faker->numberBetween(0, 20),
            'participations_competitions' => $this->faker->numberBetween(0, 15),
            'trophies_gagnes' => $this->faker->numberBetween(0, 10),
        ];
    }
}