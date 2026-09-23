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
        // 1. Rename question_libs to questions and add fields
        Schema::rename('question_libs', 'questions');

        Schema::table('questions', function (Blueprint $table) {
            $table->integer('ordre')->nullable();
            $table->float('bareme')->nullable();
            $table->foreignId('qcm_id')
                  ->nullable()
                  ->constrained('qcms')
                  ->onDelete('cascade');
        });

        // 2. Update reponse_qcms table (replace question_qcm_id with question_id)
        Schema::table('reponse_qcms', function (Blueprint $table) {
            $table->dropForeign(['question_qcm_id']);
            $table->dropColumn('question_qcm_id');

            $table->foreignId('question_id')
                  ->nullable()
                  ->constrained('questions')
                  ->onDelete('cascade');
        });

        // 3. Drop question_qcms
        Schema::dropIfExists('question_qcms');

        // 4. Update realisation_ua_projet_reponse_qcm table
        Schema::table('realisation_ua_projet_reponse_qcm', function (Blueprint $table) {
            $table->dropForeign('ruprq_realisation_ua_projet_fk');
            $table->renameColumn('realisation_ua_projet_id', 'realisation_ua_prototype_id');
            $table->foreign('realisation_ua_prototype_id', 'ruptrq_realisation_ua_prototype_fk')
                  ->references('id')->on('realisation_ua_prototypes')->onDelete('cascade');
        });
        Schema::rename('realisation_ua_projet_reponse_qcm', 'realisation_ua_prototype_reponse_qcm');
    }

    public function down(): void
    {
        // Reverse operations
        Schema::rename('realisation_ua_prototype_reponse_qcm', 'realisation_ua_projet_reponse_qcm');
        Schema::table('realisation_ua_projet_reponse_qcm', function (Blueprint $table) {
            $table->dropForeign('ruptrq_realisation_ua_prototype_fk');
            $table->renameColumn('realisation_ua_prototype_id', 'realisation_ua_projet_id');
            $table->foreign('realisation_ua_projet_id', 'ruprq_realisation_ua_projet_fk')
                  ->references('id')->on('realisation_ua_projets')->onDelete('cascade');
        });

        Schema::table('reponse_qcms', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropColumn('question_id');
            $table->foreignId('question_qcm_id')->nullable()->constrained('question_qcms')->onDelete('cascade');
        });

        Schema::create('question_qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->integer('ordre')->nullable();
            $table->float('bareme')->nullable();
            $table->foreignId('qcm_id')->constrained('qcms')->onDelete('cascade');
            $table->foreignId('question_lib_id')->constrained('questions')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['qcm_id']);
            $table->dropColumn(['ordre', 'bareme', 'qcm_id']);
        });

        Schema::rename('questions', 'question_libs');
    }
};
