<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CentreFactory extends Factory
{
    public function definition(): array
    {
        $domaines = ['formation_professionnelle', 'animation_culturelle_sportive', 'handicap', 'eps'];
        
        return [
            'denomination' => $this->faker->company . ' ' . $this->faker->randomElement(['Centre', 'Espace', 'Complexe', 'Institut']),
            'domaine_intervention' => $this->faker->randomElement($domaines),
            'localisation' => $this->faker->city,
            'superficie' => $this->faker->numberBetween(100, 5000),
            'objectifs' => $this->faker->paragraph(3),
            'composantes' => $this->faker->sentence(6),
            'nature_foncier' => $this->faker->randomElement(['Public', 'Privé', 'Mixte', 'Associatif']),
            'cout_construction' => $this->faker->numberBetween(100000, 5000000),
            'cout_equipement' => $this->faker->numberBetween(50000, 1000000),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}