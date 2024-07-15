<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsAndQuestionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('job_posting_id')->constrained('job_postings');
            $table->integer('number_of_questions');
            $table->integer('duration'); // en minutes
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->json('choices'); // JSON pour les choix multiples
            $table->string('correct_answer'); // La bonne réponse
            $table->integer('time_to_answer'); // en secondes
            $table->timestamps();
        });

        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests');
            $table->foreignId('question_id')->constrained('questions');
            $table->timestamps();
        });

        Schema::create('user_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidats');
            $table->foreignId('test_id')->constrained('tests');
            $table->foreignId('question_id')->constrained('questions');
            $table->string('response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_responses');
        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('tests');
    }
}
