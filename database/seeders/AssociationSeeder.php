<?php
// database/seeders/AssociationSeeder.php

namespace Database\Seeders;

use App\Models\Association;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AssociationSeeder extends Seeder
{
    public function run()
    {
        // Get first user as creator
        $user = User::first();

        $associations = [
            [
                'nom_asso_ar' => 'جمعية الأمل للتنمية',
                'nom_de_l_asso' => 'Association Espoir pour le Développement',
                'adresse' => '123 Avenue Hassan II',
                'jeagraphie' => 'Agadir',
                'date_de_creation' => Carbon::create(2018, 3, 15),
                'tel' => '0528123456',
                'remarque' => 'Association active dans le domaine du développement rural',
                'nombreBeneficiaire' => 150,
                'email' => 'contact@espoir.ma',
                'site_web' => 'https://espoir.ma',
                'statut_juridique' => 'Association d\'utilité publique',
                'numero_agrement' => 'AGR12345',
                'date_agrement' => Carbon::create(2018, 4, 20),
                'domaine_activite' => 'Développement rural, éducation',
                'budget_annuel' => 500000.00,
                'nombre_employes' => 8,
                'secteur_id' => 1, // Développement
                'districts_id' => 1, // Agadir
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'nom_asso_ar' => 'جمعية نور للتعليم',
                'nom_de_l_asso' => 'Association Nour pour l\'Éducation',
                'adresse' => '45 Rue Mohammed V',
                'jeagraphie' => 'Marrakech',
                'date_de_creation' => Carbon::create(2020, 7, 10),
                'tel' => '0524765432',
                'remarque' => 'Spécialisée dans l\'éducation des enfants défavorisés',
                'nombreBeneficiaire' => 200,
                'email' => 'info@nour-education.ma',
                'site_web' => null,
                'statut_juridique' => 'Association reconnue',
                'numero_agrement' => 'AGR67890',
                'date_agrement' => Carbon::create(2020, 8, 15),
                'domaine_activite' => 'Éducation, soutien scolaire',
                'budget_annuel' => 300000.00,
                'nombre_employes' => 5,
                'secteur_id' => 2, // Éducation
                'districts_id' => 2, // Marrakech
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'nom_asso_ar' => 'جمعية الخير للصحة',
                'nom_de_l_asso' => 'Association Al Khair pour la Santé',
                'adresse' => '78 Boulevard Abdelkrim Khattabi',
                'jeagraphie' => 'Casablanca',
                'date_de_creation' => Carbon::create(2015, 1, 25),
                'tel' => '0522987654',
                'remarque' => 'Soins médicaux gratuits pour les personnes démunies',
                'nombreBeneficiaire' => 500,
                'email' => 'sante@alkhair.ma',
                'site_web' => 'https://alkhair-sante.ma',
                'statut_juridique' => 'Association caritative',
                'numero_agrement' => 'AGR11111',
                'date_agrement' => Carbon::create(2015, 2, 10),
                'domaine_activite' => 'Soins médicaux, prévention',
                'budget_annuel' => 800000.00,
                'nombre_employes' => 12,
                'secteur_id' => 1, // Santé
                'districts_id' => 3, // Casablanca
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'nom_asso_ar' => 'جمعية الأرض للبيئة',
                'nom_de_l_asso' => 'Association Terre pour l\'Environnement',
                'adresse' => '22 Avenue Al Massira',
                'jeagraphie' => 'Rabat',
                'date_de_creation' => Carbon::create(2019, 11, 5),
                'tel' => '0537123456',
                'remarque' => 'Protection de l\'environnement et sensibilisation',
                'nombreBeneficiaire' => 80,
                'email' => 'contact@terre-environnement.ma',
                'site_web' => null,
                'statut_juridique' => 'Association environnementale',
                'numero_agrement' => 'AGR22222',
                'date_agrement' => Carbon::create(2019, 12, 1),
                'domaine_activite' => 'Protection environnementale, recyclage',
                'budget_annuel' => 150000.00,
                'nombre_employes' => 3,
                'secteur_id' => 4, // Environnement
                'districts_id' => 4, // Rabat
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'nom_asso_ar' => 'جمعية المستقبل للزراعة',
                'nom_de_l_asso' => 'Association Avenir pour l\'Agriculture',
                'adresse' => 'Rue des Oliviers, Douar Sidi Ali',
                'jeagraphie' => 'Fès',
                'date_de_creation' => Carbon::create(2017, 5, 20),
                'tel' => '0535654321',
                'remarque' => 'Développement de l\'agriculture biologique',
                'nombreBeneficiaire' => 120,
                'email' => 'agriculture@avenir.ma',
                'site_web' => 'https://avenir-agriculture.ma',
                'statut_juridique' => 'Association agricole',
                'numero_agrement' => 'AGR33333',
                'date_agrement' => Carbon::create(2017, 6, 15),
                'domaine_activite' => 'Agriculture biologique, formation',
                'budget_annuel' => 250000.00,
                'nombre_employes' => 6,
                'secteur_id' => 3, // Agriculture
                'districts_id' => 5, // Fès
                'is_active' => true,
                'created_by' => $user->id,
            ],
        ];

        foreach ($associations as $association) {
            Association::create($association);
        }
    }
}