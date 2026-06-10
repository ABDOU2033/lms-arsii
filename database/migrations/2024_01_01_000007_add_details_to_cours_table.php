<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->string('categorie')->nullable()->after('description');
            $table->string('niveau_scolaire')->nullable()->after('categorie');
            $table->string('annee_universitaire', 9)->nullable()->after('niveau_scolaire');
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropColumn(['categorie', 'niveau_scolaire', 'annee_universitaire']);
        });
    }
};
