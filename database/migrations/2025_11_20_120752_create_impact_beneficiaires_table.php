<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('impact_beneficiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centre_id')->constrained()->onDelete('cascade');
            $table->enum('domaine', [
                'formation_pro', 
                'animation_culturelle_sportive', 
                'handicap', 
                'eps'
            ])->default('formation_pro');
            
            // Champs communs à tous les domaines
            $table->string('intitule_filiere_discipline');
            $table->integer('nombre_inscrits_hommes')->default(0);
            $table->integer('nombre_inscrits_femmes')->default(0);
            $table->integer('heures_par_beneficiaire')->default(0);
            $table->integer('nombre_abandons')->default(0);
            $table->decimal('masse_salariale', 15, 2)->default(0);
            $table->decimal('charges_globales', 15, 2)->default(0);
            $table->year('annee');
            
            $table->timestamps();
            
            // Index
            $table->index(['centre_id', 'domaine']);
            $table->index('annee');
            $table->index('domaine');
        });
    }

    public function down()
    {
        Schema::dropIfExists('impact_beneficiaires');
    }
};
