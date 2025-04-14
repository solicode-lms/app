<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->foreignId('section_widget_id')
                  ->nullable()
                  ->after('reference') // ou 'icon', selon ton ordre préféré
                  ->constrained('section_widgets')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropForeign(['section_widget_id']);
            $table->dropColumn('section_widget_id');
        });
    }
};
