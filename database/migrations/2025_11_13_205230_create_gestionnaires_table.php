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
        Schema::create('gestionnaires', function (Blueprint $table) {
            $table->id();
              $table->foreignId('centre_id')->constrained()->onDelete('cascade');
            $table->string('association');
            $table->string('recepisse_definitif');
            $table->json('liste_membres'); // Stocke la liste des membres en JSON
            $table->string('liasse_fiscale');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestionnaires');
    }
};
