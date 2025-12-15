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
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impact_beneficiaire_id')->constrained('impact_beneficiaires')->onDelete('cascade');
            $table->string('nom');
            $table->enum('type', [
                'institutionnel', 
                'entreprise', 
                'ong', 
                'association', 
                'autre'
            ])->default('institutionnel');
            $table->string('contact_nom')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_email')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('impact_beneficiaire_id');
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('partenaires');
    }
};
