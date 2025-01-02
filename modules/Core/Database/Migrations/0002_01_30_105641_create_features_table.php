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
        Schema::create('features', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('name')->unique(); // Nom de la fonctionnalité
            $table->longText('description')->nullable(); // Description de la fonctionnalité
            $table->foreignId('domain_id')->constrained('feature_domains')->onDelete('cascade'); // Lien avec feature_domains
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
        Schema::dropIfExists('features');
    }
};
