<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('session_formations', function (Blueprint $table) {
            $table->string('code')->nullable()->after('reference');
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('session_formations', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
