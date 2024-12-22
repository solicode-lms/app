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
        Schema::create('add_controller_id_to_permissions', function (Blueprint $table) {
            $table->foreignId('controller_id')
            ->nullable()
            ->constrained('sys_controller')
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
