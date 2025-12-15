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
        Schema::create('ressource_financieres', function (Blueprint $table) {
            $table->id();
              $table->foreignId('centre_id')->constrained()->onDelete('cascade');
            $table->integer('budget_annee');
            $table->decimal('total_depenses', 15, 2);
            $table->decimal('total_recettes', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressource_financieres');
    }
};
