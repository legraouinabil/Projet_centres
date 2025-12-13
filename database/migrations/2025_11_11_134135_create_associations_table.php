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
    Schema::create('associations', function (Blueprint $table) {
      $table->id();
      $table->string('nom_asso_ar');
      $table->string('nom_de_l_asso');
      $table->text('adresse');
      $table->string('jeagraphie');
      $table->date('date_de_creation');
      $table->string('tel');
      $table->text('remarque')->nullable();
      $table->integer('nombreBeneficiaire')->default(0);
      $table->string('email')->nullable();
      $table->string('site_web')->nullable();
      $table->string('statut_juridique')->nullable();
      $table->string('numero_agrement')->nullable();
      $table->date('date_agrement')->nullable();
      $table->string('domaine_activite')->nullable();
      $table->decimal('budget_annuel', 15, 2)->default(0);
      $table->integer('nombre_employes')->default(0);
      $table->boolean('is_active')->default(true);

      $table->foreignId('secteur_id')->constrained()->onDelete('cascade');
      $table->foreignId('districts_id')->constrained('districts')->onDelete('cascade');
      $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('associations');
  }
};
