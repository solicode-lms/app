<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Décaler les ordres existants pour faire de la place à la position 5
        \DB::table('phase_projets')->where('ordre', '>=', 5)->increment('ordre');

        // 2. Insérer la nouvelle phase "Live Coding"
        \DB::table('phase_projets')->insert([
            'reference' => 'LIVE_CODING',
            'code' => 'LIVE_CODING',
            'nom' => 'Live Coding',
            'description' => 'Session de code en direct sur le prototype',
            'ordre' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Supprimer la phase "Live Coding"
        \DB::table('phase_projets')->where('code', 'LIVE_CODING')->delete();

        // 2. Rétablir les ordres (décrémenter ceux qui sont > 5)
        // Note: Ceux qui étaient >= 5 sont devenus >= 6.
        // On veut que 6 devienne 5, 7 devienne 6, etc.
        \DB::table('phase_projets')->where('ordre', '>', 5)->decrement('ordre');
    }
};
