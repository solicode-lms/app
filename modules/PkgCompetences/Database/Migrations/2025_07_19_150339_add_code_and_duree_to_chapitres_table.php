<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('chapitres', function (Blueprint $table) {
            $table->string('code')->after('id')->unique()->nullable();
            $table->float('duree_en_heure')->after('description')->default(0);
        });
    }

    public function down()
    {
        Schema::table('chapitres', function (Blueprint $table) {
            $table->dropColumn(['code', 'duree_en_heure']);
        });
    }
};
