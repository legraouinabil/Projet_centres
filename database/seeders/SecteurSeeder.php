<?php
// database/seeders/SecteurSeeder.php

namespace Database\Seeders;

use App\Models\Secteur;
use Illuminate\Database\Seeder;

class SecteurSeeder extends Seeder
{
    public function run()
    {
        $secteurs = [
            ['nom_secteur_ar' => 'الصحة', 'nom_secteur_fr' => 'Santé', 'description' => 'Secteur de la santé'],
            ['nom_secteur_ar' => 'التعليم', 'nom_secteur_fr' => 'Éducation', 'description' => 'Secteur de l\'éducation'],
            ['nom_secteur_ar' => 'الزراعة', 'nom_secteur_fr' => 'Agriculture', 'description' => 'Secteur agricole'],
            ['nom_secteur_ar' => 'البيئة', 'nom_secteur_fr' => 'Environnement', 'description' => 'Secteur environnemental'],
            ['nom_secteur_ar' => 'التنمية', 'nom_secteur_fr' => 'Développement', 'description' => 'Secteur du développement'],
        ];

        foreach ($secteurs as $secteur) {
            Secteur::create($secteur);
        }
    }
}