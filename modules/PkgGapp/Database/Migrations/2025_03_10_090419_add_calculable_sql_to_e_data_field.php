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
    public function up()
    {
        Schema::table('e_data_fields', function (Blueprint $table) {
            $table->text('calculable_sql')->default(false)->after('calculable'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('e_data_fields', function (Blueprint $table) {
            $table->dropColumn('text');
        });
    }
};
