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
        // Inscriptions
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->timestamp('date_inscription')->useCurrent();
            $table->timestamps();
        });

        // Lecons
        Schema::create('lecons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu');
            $table->enum('type', ['video', 'texte', 'pdf', 'presentation']);
            $table->integer('ordre');
            $table->timestamps();
        });

        // Quizzes
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->string('titre');
            $table->integer('duree'); // minutes
            $table->integer('note_max');
            $table->timestamps();
        });

        // Questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('enonce');
            $table->enum('type', ['qcm', 'vrai_faux', 'texte_libre']);
            $table->integer('points');
            $table->timestamps();
        });

        // ChoixReponses
        Schema::create('choix_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('contenu');
            $table->boolean('est_correcte');
            $table->timestamps();
        });

        // Reponses
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('contenu');
            $table->boolean('est_correcte');
            $table->timestamp('date_reponse')->useCurrent();
            $table->integer('score_obtenu');
            $table->timestamps();
            $table->unique(['etudiant_id', 'question_id']);
        });

        // Progressions
        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->integer('pourcentage');
            $table->timestamp('date_maj')->useCurrent();
            $table->timestamps();
        });

        // LeconsVues
        Schema::create('lecons_vues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('cours')->onDelete('cascade');
            $table->foreignId('lecon_id')->constrained('lecons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecons_vues');
        Schema::dropIfExists('progressions');
        Schema::dropIfExists('reponses');
        Schema::dropIfExists('choix_reponses');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('lecons');
        Schema::dropIfExists('inscriptions');
    }
};