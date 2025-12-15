<?php

namespace Database\Factories;

use App\Models\Centre;
use Illuminate\Database\Eloquent\Factories\Factory;

class RessourceHumaineFactory extends Factory
{
    public function definition(): array
    {
        $postes = ['Directeur', 'Formateur', 'Animateur', 'Éducateur', 'Assistant', 'Comptable', 'Secrétaire'];
        $contrats = ['CDI', 'CDD', 'Stage', 'Consultant', 'Bénévolat'];
        
        return [
            'centre_id' => Centre::factory(),
            'poste' => $this->faker->randomElement($postes),
            'nom_prenom' => $this->faker->name(),
            'salaire' => $this->faker->numberBetween(3000, 20000),
            'type_contrat' => $this->faker->randomElement($contrats),
        ];
    }
}