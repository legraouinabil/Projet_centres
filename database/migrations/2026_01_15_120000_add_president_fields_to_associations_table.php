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
        Schema::table('associations', function (Blueprint $table) {
            $table->string('president_name')->nullable()->after('email');
            $table->string('president_email')->nullable()->after('president_name');
            $table->string('president_cin')->nullable()->after('president_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('associations', function (Blueprint $table) {
            $table->dropColumn(['president_name', 'president_email', 'president_cin']);
        });
    }
};
