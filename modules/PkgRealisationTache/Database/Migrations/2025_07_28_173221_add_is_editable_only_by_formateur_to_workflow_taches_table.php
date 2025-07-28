<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('workflow_taches', function (Blueprint $table) {
            $table->boolean('is_editable_only_by_formateur')
                  ->default(false)
                  ->after('description')
                  ->comment('Indique si cet état est modifiable uniquement par le formateur');
        });
    }

    public function down()
    {
        Schema::table('workflow_taches', function (Blueprint $table) {
            $table->dropColumn('is_editable_only_by_formateur');
        });
    }
};
