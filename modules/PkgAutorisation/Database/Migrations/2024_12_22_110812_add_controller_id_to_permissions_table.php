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
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignId('controller_id')
            ->nullable()
            ->after('guard_name') // Place la colonne après "guard_name"
            ->constrained('sys_controllers')
            ->onDelete('cascade'); // Clé étrangère vers "sys_controller"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_controller_id_to_permissions');
    }
};
