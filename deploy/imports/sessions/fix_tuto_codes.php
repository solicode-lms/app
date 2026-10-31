<?php
/**
 * fix_tuto_codes.php
 *
 * Normalise les codes et références de tutoriels (chapitres) en base de données :
 * Transforme les formats malformés à 4 parties (ex: T.152.21.1 / DWB-T.152.21.1)
 * en format canonique strict à 3 parties (ex: T.152.211 / DWB-T.152.211).
 *
 * Usage:
 *   php deploy/imports/sessions/fix_tuto_codes.php --dry-run   (Simulation)
 *   php deploy/imports/sessions/fix_tuto_codes.php --apply     (Application réelle)
 */

require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\PkgCompetences\Models\Chapitre;
use Illuminate\Support\Facades\DB;

$options = getopt("", ["dry-run", "apply", "filiere:"]);
$isApply = isset($options['apply']);
$filiereFilter = isset($options['filiere']) ? strtoupper(trim($options['filiere'])) : null;

echo "\n\033[36m===================================================================\033[0m\n";
echo "\033[1;36m  🔧 NORMALISATION DES CODES ET RÉFÉRENCES DE TUTORIELS (CHAPITRES)\033[0m\n";
echo "\033[36m===================================================================\033[0m\n";

if ($isApply) {
    echo "Mode : \033[1;31mAPPLICATION RÉELLE (--apply)\033[0m\n\n";
} else {
    echo "Mode : \033[1;33mSIMULATION LECTURE SEULE (--dry-run)\033[0m\n";
    echo "Pour appliquer réellement les changements en base, relancez avec l'option --apply\n\n";
}

$query = Chapitre::query();
if ($filiereFilter) {
    $query->where('reference', 'LIKE', $filiereFilter . '-%');
}

$chapitres = $query->get();
$malformed = [];

foreach ($chapitres as $c) {
    $parts = explode('.', $c->code);
    if (count($parts) > 3) {
        $prefix = $parts[0]; // T
        $mid = $parts[1];    // ex: 152
        $suffix = '';
        for ($i = 2; $i < count($parts); $i++) {
            $suffix .= $parts[$i];
        }
        $normalizedCode = "$prefix.$mid.$suffix"; // ex: T.152.211

        $refParts = explode('-', $c->reference);
        $refPrefix = $refParts[0] ?? 'DWB';
        $normalizedRef = "$refPrefix-$normalizedCode"; // ex: DWB-T.152.211

        $malformed[] = [
            'model' => $c,
            'id' => $c->id,
            'old_code' => $c->code,
            'old_ref' => $c->reference,
            'new_code' => $normalizedCode,
            'new_ref' => $normalizedRef,
            'nom' => $c->nom,
        ];
    }
}

echo "Total chapitres audités : " . $chapitres->count() . "\n";
echo "Chapitres malformés détectés (>3 parties avec point) : \033[1;33m" . count($malformed) . "\033[0m\n\n";

if (empty($malformed)) {
    echo "\033[32m✅ Aucun chapitre malformé trouvé en base de données. Tout est conforme !\033[0m\n\n";
    exit(0);
}

$renamedCount = 0;
$duplicatesCleaned = 0;

if ($isApply) {
    DB::beginTransaction();
}

try {
    foreach ($malformed as $item) {
        $model = $item['model'];
        $newCode = $item['new_code'];
        $newRef = $item['new_ref'];

        // Vérifier si un autre chapitre possède déjà ce code ou cette référence canonique
        $conflict = Chapitre::where('id', '!=', $item['id'])
            ->where(function ($q) use ($newRef, $newCode) {
                $q->where('reference', $newRef)->orWhere('code', $newCode);
            })
            ->first();

        $realCount = DB::table('realisation_chapitres')->where('chapitre_id', $item['id'])->count();
        $tachCount = DB::table('taches')->where('chapitre_id', $item['id'])->count();

        if ($conflict) {
            $conflictReal = DB::table('realisation_chapitres')->where('chapitre_id', $conflict->id)->count();
            $conflictTach = DB::table('taches')->where('chapitre_id', $conflict->id)->count();

            // Si le chapitre malformé est un doublon vide (0 réalisation) et que l'original existe déjà avec l'historique
            if ($realCount === 0 && $tachCount === 0) {
                echo "🗑️  [DOUBLON VIDE] ID {$item['id']} ({$item['old_code']}) ➔ Doublon de ID {$conflict->id} ({$conflict->code}, {$conflictReal} réalisations)\n";
                if ($isApply) {
                    $model->delete();
                }
                $duplicatesCleaned++;
                continue;
            } elseif ($conflictReal === 0 && $conflictTach === 0) {
                // Cas inverse : le conflit est vide, l'actuel a des réalisations
                echo "🔄 [CONFLIT VIDE NETTOYÉ] Suppression doublon vide ID {$conflict->id} puis renommage ID {$item['id']}\n";
                if ($isApply) {
                    $conflict->delete();
                    $model->code = $newCode;
                    $model->reference = $newRef;
                    $model->save();
                }
                $renamedCount++;
                continue;
            } else {
                echo "⚠️ \033[31m[CONFLIT NON RÉSOLU AUTOMATIQUEMENT]\033[0m ID {$item['id']} et ID {$conflict->id} ont tous deux des réalisations !\n";
                continue;
            }
        }

        // Pas de conflit : mise à jour simple
        echo "✏️  [NORMALISÉ] ID {$item['id']} : {$item['old_code']} ➔ {$newCode} | {$item['old_ref']} ➔ {$newRef} ({$realCount} réalisations)\n";
        if ($isApply) {
            $model->code = $newCode;
            $model->reference = $newRef;
            $model->save();
        }
        $renamedCount++;
    }

    if ($isApply) {
        DB::commit();
        echo "\n\033[32m===================================================================\033[0m\n";
        echo "\033[1;32m  ✅ TRANSACTION VALIDÉE AVEC SUCCÈS !\033[0m\n";
        echo "  - Chapitres renommés vers format canonique : \033[1;32m$renamedCount\033[0m\n";
        echo "  - Doublons vides orphelins résolus : \033[1;32m$duplicatesCleaned\033[0m\n";
        echo "\033[32m===================================================================\033[0m\n\n";
    } else {
        echo "\n-------------------------------------------------------------------\n";
        echo "Bilan simulation : \033[36m$renamedCount\033[0m chapitres à renommer, \033[36m$duplicatesCleaned\033[0m doublons vides à résoudre.\n";
        echo "Relancez avec \033[1;32m--apply\033[0m pour exécuter.\n\n";
    }
} catch (\Throwable $e) {
    if ($isApply) {
        DB::rollBack();
    }
    echo "\n\033[31m[ERREUR CRITIQUE] Transaction annulée : " . $e->getMessage() . "\033[0m\n\n";
    exit(1);
}
