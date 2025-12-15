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
        Schema::create('impact_formation_pros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impact_beneficiaire_id')->constrained('impact_beneficiaires')->onDelete('cascade');
            
            // Spécifiques formation pro
            $table->integer('nombre_filieres')->default(1);
            $table->integer('nombre_laureats')->default(0);
            $table->decimal('taux_insertion_professionnelle', 5, 2)->nullable();
            $table->decimal('moyenne_premier_salaire', 10, 2)->nullable();
            $table->integer('nombre_inscrits_1ere_annee')->default(0);
            $table->integer('nombre_inscrits_2eme_annee')->default(0);
            
            $table->timestamps();
            
            // Index
            $table->index('impact_beneficiaire_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('impact_formation_pros');
    }
};
