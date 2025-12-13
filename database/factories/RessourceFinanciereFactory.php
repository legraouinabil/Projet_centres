<?php

namespace Database\Factories;

use App\Models\Centre;
use Illuminate\Database\Eloquent\Factories\Factory;

class RessourceFinanciereFactory extends Factory
{
    public function definition(): array
    {
        $recettes = $this->faker->numberBetween(50000, 500000);
        $depenses = $recettes * $this->faker->randomFloat(2, 0.7, 1.2);
        
        return [
            'centre_id' => Centre::factory(),
            'budget_annee' => $this->faker->numberBetween(2020, 2024),
            'total_recettes' => $recettes,
            'total_depenses' => $depenses,
        ];
    }
}