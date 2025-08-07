<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('realisation_projets', function (Blueprint $table) {
            $table->double('progression_cache')->nullable()->after('etats_realisation_projet_id');
            $table->double('note_cache')->nullable()->after('progression_cache');
            $table->double('bareme_cache')->nullable()->after('note_cache');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down(): void
{
    Schema::table('realisation_projets', function (Blueprint $table) {
        $table->dropColumn([
            'progression_cache',
            'note_cache',
            'bareme_cache',
        ]);
    });
}
};
