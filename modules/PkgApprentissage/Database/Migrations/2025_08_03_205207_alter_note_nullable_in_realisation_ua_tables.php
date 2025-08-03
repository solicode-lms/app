<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('realisation_ua_prototypes', function (Blueprint $table) {
            $table->double('note')->nullable()->default(null)->change();
        });

        Schema::table('realisation_ua_projets', function (Blueprint $table) {
            $table->double('note')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('realisation_ua_prototypes', function (Blueprint $table) {
            $table->double('note')->default(0)->change();
        });

        Schema::table('realisation_ua_projets', function (Blueprint $table) {
            $table->double('note')->default(0)->change();
        });
    }
};
