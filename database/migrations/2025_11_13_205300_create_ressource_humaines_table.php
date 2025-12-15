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
        Schema::create('ressource_humaines', function (Blueprint $table) {
            $table->id();
             $table->foreignId('centre_id')->constrained()->onDelete('cascade');
            $table->string('poste');
            $table->string('nom_prenom');
            $table->decimal('salaire', 10, 2);
            $table->string('type_contrat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressource_humaines');
    }
};
