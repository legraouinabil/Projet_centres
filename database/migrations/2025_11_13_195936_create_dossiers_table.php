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
        Schema::create('dossiers', function (Blueprint $table) {
      $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('reference')->unique();
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->dateTime('due_date')->nullable();
             $table->foreignId('association_id')->nullable()->constrained('associations')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
