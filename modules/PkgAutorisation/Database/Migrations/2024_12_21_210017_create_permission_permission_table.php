<?php
// 2024_12_21_192802_create_permission_hierarchy_table



namespace Modules\PkgAutorisation\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('permission_permission', function (Blueprint $table) {

        //     $table->timestamps();
           
        //     $table->foreignId('permission_id')->constrained('permissions');
        //     $table->foreignId('permission_id')->constrained('permissions');

        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('permission_permission');
    }
};
