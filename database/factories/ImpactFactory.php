<?php

namespace Database\Factories;

use App\Models\Centre;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImpactFactory extends Factory
{
    public function definition(): array
    {
        $activites = ['formation_professionnelle', 'animation_culturelle_sportive', 'handicap', 'eps'];
        $hommes = $this->faker->numberBetween(10, 200);
        $femmes = $this->faker->numberBetween(10, 200);
        $total = $hommes + $femmes;
        
        return [
            'centre_id' => Centre::factory(),
            'type_activite' => $this->faker->randomElement($activites),
            'nombre_inscrits_hommes' => $hommes,
            'nombre_inscrits_femmes' => $femmes,
            'heures_par_beneficiaire' => $this->faker->numberBetween(20, 500),
            'nombre_abandons' => $this->faker->numberBetween(0, $total * 0.3),
            'nombre_laureats' => $this->faker->numberBetween(0, $total * 0.8),
            'taux_insertion' => $this->faker->optional(0.7)->randomFloat(2, 30, 95),
            'salaire_moyen' => $this->faker->optional(0.5)->numberBetween(2000, 8000),
            'cout_revient_par_beneficiaire' => $this->faker->numberBetween(500, 5000),
        ];
    }
}