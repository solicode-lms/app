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
        // Table pivot pour relier les permissions et les features
        Schema::create('feature_permission', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade'); // Clé étrangère vers features
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade'); // Clé étrangère vers permissions
            $table->string('reference')->unique();
            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_permission');
    }
};
