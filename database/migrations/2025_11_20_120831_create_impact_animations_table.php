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
        Schema::create('impact_animations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impact_beneficiaire_id')->constrained('impact_beneficiaires')->onDelete('cascade');
            
            // Spécifiques animation
            $table->integer('nombre_disciplines')->default(1);
            $table->integer('nombre_inscrits_ecoles')->default(0);
            $table->integer('nombre_inscrits_particuliers')->default(0);
            $table->integer('nombre_conventions')->default(0);
            $table->integer('nombre_evenements_organises')->default(0);
            $table->integer('nombre_participations_competitions')->default(0);
            $table->integer('nombre_trophees_gagnes')->default(0);
            
            $table->timestamps();
            
            // Index
            $table->index('impact_beneficiaire_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('impact_animations');
    }
};
