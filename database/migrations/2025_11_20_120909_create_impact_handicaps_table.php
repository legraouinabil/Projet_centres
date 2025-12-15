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
        Schema::create('impact_handicaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impact_beneficiaire_id')->constrained('impact_beneficiaires')->onDelete('cascade');
            
            // Spécifiques handicap
            $table->integer('nombre_handicaps_traites')->default(1);
            $table->integer('heures_medecin_an')->default(0);
            $table->integer('heures_assistant_social_an')->default(0);
            $table->integer('heures_orthophoniste_an')->default(0);
            $table->integer('heures_kinesitherapie_an')->default(0);
            $table->integer('heures_psychologue_an')->default(0);
            
            $table->timestamps();
            
            // Index
            $table->index('impact_beneficiaire_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('impact_handicaps');
    }
};
