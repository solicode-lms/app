<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sys_modules', function (Blueprint $table) {
            $table->renameColumn('order', 'ordre');
        });
    }

    public function down()
    {
        Schema::table('sys_modules', function (Blueprint $table) {
            $table->renameColumn('ordre', 'order');
        });
    }
};