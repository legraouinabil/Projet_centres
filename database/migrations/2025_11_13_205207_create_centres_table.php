<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('centres', function (Blueprint $table) {
            $table->id();
             $table->string('denomination');
            $table->enum('domaine_intervention', ['formation_professionnelle', 'animation_culturelle_sportive', 'handicap', 'eps']);
            $table->string('localisation');
            $table->decimal('superficie', 10, 2);
            $table->text('objectifs');
            $table->text('composantes');
            $table->string('nature_foncier');
            $table->decimal('cout_construction', 15, 2);
            $table->decimal('cout_equipement', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centres');
    }
};
