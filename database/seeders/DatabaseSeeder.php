<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Centre;
use App\Models\District;
use App\Models\Gestionnaire;
use App\Models\Impact;
use App\Models\Partenaire;
use App\Models\RessourceFinanciere;
use App\Models\RessourceHumaine;
use App\Models\Secteur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
      
         \App\Models\User::factory()->create([
            'name' => 'Test User',
           'email' => 'test@example.com',
           'password' =>Hash::make('00000000'),
            'role'  =>'user'
        ]);

         \App\Models\User::factory()->create([
            'name' => 'Test Admin',
           'email' => 'admin@admin.com',
           'password' =>Hash::make('00000000'),
            'role'  =>'admin'
    ]);



  $this->call([
            DistrictSeeder::class,
            SecteurSeeder::class,
          AssociationSeeder::class
        ]);

  // Créer 10 centres
        $centres = Centre::factory()->count(10)->create();

        // Pour chaque centre, créer des données associées
        $centres->each(function ($centre) {
            // Gestionnaire
            Gestionnaire::factory()->create(['centre_id' => $centre->id]);

            // Ressources humaines (2-6 par centre)
            RessourceHumaine::factory()
                ->count(rand(2, 6))
                ->create(['centre_id' => $centre->id]);

            // Ressources financières (1-3 par centre)
            RessourceFinanciere::factory()
                ->count(rand(1, 3))
                ->create(['centre_id' => $centre->id]);

            // Impacts (1-4 par centre)
            $impacts = Impact::factory()
                ->count(rand(1, 4))
                ->create(['centre_id' => $centre->id]);

            // Pour chaque impact, créer 0-3 partenaires
            $impacts->each(function ($impact) {
                Partenaire::factory()
                    ->count(rand(0, 3))
                    ->create(['impact_id' => $impact->id]);
            });
        });

        $this->command->info('Données fictives générées avec succès!');
        $this->command->info('- 10 Centres');
        $this->command->info('- 10 Gestionnaires');
        $this->command->info('- ~40 Ressources Humaines');
        $this->command->info('- ~20 Ressources Financières');
        $this->command->info('- ~30 Impacts');
        $this->command->info('- ~45 Partenaires');
    


    $this->call([
            DistrictSeeder::class,
            SecteurSeeder::class,
          AssociationSeeder::class
        ]);
    }
}
