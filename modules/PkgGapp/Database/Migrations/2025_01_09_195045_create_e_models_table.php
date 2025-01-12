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
        Schema::create('e_models', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); 
            $table->string('code'); 
            $table->string('tableName'); 
            $table->string('icon')->nullable(); // Icône associée au modèle
            $table->boolean('isPivotTable'); 
            $table->text('description')->nullable(); // Description facultative
            $table->foreignId('e_package_id')->constrained('e_packages')->onDelete('cascade'); // Relation avec IPackage
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_models');
    }
};
