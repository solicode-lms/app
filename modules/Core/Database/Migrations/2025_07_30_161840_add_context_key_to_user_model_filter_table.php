<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_model_filters', function (Blueprint $table) {
            $table->string('context_key')->nullable()->after('model_name');
        });
    }

    public function down()
    {
        Schema::table('user_model_filters', function (Blueprint $table) {
            $table->dropColumn('context_key');
        });
    }
};
