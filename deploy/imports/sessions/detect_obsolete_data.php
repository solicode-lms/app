<?php
/**
 * detect_obsolete_data.php
 * 
 * Analyse différentielle entre les fichiers CSV d'importation et la base de données Solicode LMS.
 * Détecte les tutoriels (chapitres), unités d'apprentissage et alignements existants en BDD
 * pour la filière et le niveau ciblés mais qui n'apparaissent plus dans les nouveaux CSV d'autoformation.
 * 
 * Usage:
 *   php deploy/imports/sessions/detect_obsolete_data.php --filiere=DWB
 *   php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB [--niveau=3]
 *   php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --delete
 */

// 1. Initialisation de l'environnement Laravel
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Modules\PkgFormation\Models\Filiere;
use Modules\PkgCompetences\Models\Chapitre;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\PkgSessions\Models\AlignementUa;

// 2. Analyse des arguments en ligne de commande
$options = getopt("", ["filiere:", "delete", "dir:", "niveau:"]);

$filiereInput = $options['filiere'] ?? null;
$shouldDelete = isset($options['delete']);
$customDir = $options['dir'] ?? null;
$niveauInput = $options['niveau'] ?? null;

if (!$filiereInput) {
    echo "\n\033[31m[ERREUR] L'argument --filiere est obligatoire.\033[0m\n";
    echo "Exemple : php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB [--niveau=3] [--delete]\n\n";
    exit(1);
}

// Normalisation de la filière
$filiereCode = strtoupper(trim($filiereInput));
$filiere = Filiere::where('code', $filiereCode)
    ->orWhere('reference', $filiereCode)
    ->first();

if (!$filiere) {
    echo "\n\033[31m[ERREUR] Filière introuvable pour le code/référence '$filiereCode'.\033[0m\n\n";
    exit(1);
}

// 3. Détermination du dossier source des CSV
if ($customDir) {
    $importDir = $customDir;
} else {
    $subDir = (strpos($filiereCode, 'MOB') !== false || in_array($filiereCode, ['DM', 'DMB'])) ? 'mobile' : 'web';
    $importDir = __DIR__ . '/' . $subDir;
}

if (!is_dir($importDir)) {
    echo "\n\033[31m[ERREUR] Répertoire d'import introuvable : $importDir\033[0m\n\n";
    exit(1);
}

// Fonction utilitaire pour détecter automatiquement le niveau depuis les CSV
function detectNiveauFromCsvs(string $importDir): array {
    $files = [
        ['chapitres.csv', 'chapitre.csv'],
        ['uniteApprentissages.csv', 'uniteApprentissage.csv', 'uas.csv'],
        ['microCompetences.csv', 'microCompetence.csv'],
        ['competences.csv', 'competence.csv'],
    ];

    $levelsFound = [];

    foreach ($files as $candidates) {
        $found = null;
        foreach ($candidates as $name) {
            $p = $importDir . '/' . $name;
            if (file_exists($p)) {
                $found = $p;
                break;
            }
        }
        if (!$found) {
            continue;
        }

        if (($handle = fopen($found, 'r')) !== false) {
            $headers = fgetcsv($handle);
            if ($headers) {
                $codeIdx = array_search('code', $headers);
                if ($codeIdx !== false) {
                    while (($row = fgetcsv($handle)) !== false) {
                        if (isset($row[$codeIdx])) {
                            $code = trim($row[$codeIdx]);
                            // Patterns: C.311, D.311.1, UA.351.21, T.311.211, TUTO-UA.111.12
                            if (preg_match('/^(?:TUTO-)?(?:[A-Z]+)\.([0-9])/i', $code, $matches)) {
                                $digit = $matches[1];
                                $levelsFound[$digit] = ($levelsFound[$digit] ?? 0) + 1;
                            }
                        }
                    }
                }
            }
            fclose($handle);
        }
    }

    return $levelsFound;
}

// Résolution du niveau (argument explicite ou détection automatique)
$niveauFilter = null;
$niveauDetectionMode = 'auto';

if ($niveauInput !== null) {
    if (strtolower(trim($niveauInput)) === 'all') {
        $niveauFilter = null;
        $niveauDetectionMode = 'all';
    } else {
        $cleaned = preg_replace('/[^0-9]/', '', $niveauInput);
        if ($cleaned !== '') {
            $niveauFilter = $cleaned;
            $niveauDetectionMode = 'param';
        }
    }
} else {
    // Détection automatique depuis les CSV
    $detectedLevels = detectNiveauFromCsvs($importDir);
    if (!empty($detectedLevels)) {
        arsort($detectedLevels);
        $topLevel = array_key_first($detectedLevels);
        $niveauFilter = (string)$topLevel;
        $niveauDetectionMode = 'auto';
    }
}

echo "\n\033[36m===================================================================\033[0m\n";
$niveauBanner = $niveauFilter ? " [NIVEAU N{$niveauFilter}]" : " [TOUS NIVEAUX]";
echo "\033[1;36m  🔍 ANALYSE DIFFÉRENTIELLE DES DONNÉES - FILIÈRE {$filiere->code} ({$filiere->nom}){$niveauBanner}\033[0m\n";
echo "\033[36m===================================================================\033[0m\n";
echo "📂 Répertoire source analysé : \033[33m$importDir\033[0m\n";

if ($niveauFilter) {
    if ($niveauDetectionMode === 'auto') {
        echo "🎯 Niveau détecté automatiquement : \033[1;32mN{$niveauFilter}\033[0m (depuis les codes des CSV sources)\n";
    } else {
        echo "🎯 Niveau forcé par argument : \033[1;32mN{$niveauFilter}\033[0m (--niveau={$niveauFilter})\n";
    }
} else {
    echo "ℹ️  Aucun filtre de niveau appliqué (audit global sur toute la filière)\n";
}
echo "\n";

// Fonction utilitaire pour lire les références depuis un CSV (en gérant singulier/pluriel)
function getCsvReferences(string $importDir, array $possibleFileNames): array {
    $filePath = null;
    foreach ($possibleFileNames as $fileName) {
        $candidate = $importDir . '/' . $fileName;
        if (file_exists($candidate)) {
            $filePath = $candidate;
            break;
        }
    }

    if (!$filePath) {
        return [];
    }

    $references = [];
    if (($handle = fopen($filePath, "r")) !== false) {
        $headers = fgetcsv($handle);
        if ($headers) {
            $refIndex = array_search('reference', $headers);
            if ($refIndex !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (isset($row[$refIndex]) && trim($row[$refIndex]) !== '') {
                        $references[trim($row[$refIndex])] = true;
                    }
                }
            }
        }
        fclose($handle);
    }

    return $references;
}

// 4. Extraction des références présentes dans les nouveaux CSV d'import
$csvChapitreRefs = getCsvReferences($importDir, ['chapitres.csv', 'chapitre.csv']);
$csvUaRefs = getCsvReferences($importDir, ['uniteApprentissages.csv', 'uniteApprentissage.csv', 'uas.csv']);
$csvSessionRefs = getCsvReferences($importDir, ['sessionFormations.csv', 'sessionFormation.csv', 'sessions.csv']);
$csvAlignementRefs = getCsvReferences($importDir, ['alignementUas.csv', 'alignementUa.csv']);

echo "📊 Éléments trouvés dans les CSV d'import :\n";
echo "   - Sessions : " . count($csvSessionRefs) . "\n";
echo "   - Unités d'apprentissage (UA) : " . count($csvUaRefs) . "\n";
echo "   - Chapitres (Tutoriels) : " . count($csvChapitreRefs) . "\n";
echo "   - Alignements UA : " . count($csvAlignementRefs) . "\n\n";

if (empty($csvChapitreRefs) && empty($csvUaRefs) && empty($csvSessionRefs)) {
    echo "\033[31m[ERREUR] Aucun fichier CSV valide trouvé dans : $importDir\033[0m\n";
    echo "Veuillez d'abord déposer les fichiers CSV exportés dans ce dossier avant de lancer l'analyse :\n";
    echo "  - chapitres.csv (ou chapitre.csv)\n";
    echo "  - uniteApprentissages.csv (ou uniteApprentissage.csv)\n";
    echo "  - sessionFormations.csv (ou sessionFormation.csv)\n";
    echo "  - alignementUas.csv (ou alignementUa.csv)\n\n";
    exit(1);
}

// 5. Recherche des données existantes en BDD pour cette filière (et ce niveau)
$obsoleteItems = [];

// 5.1 Chapitres (Tutoriels) orphelins
$dbChapitresQuery = Chapitre::whereHas('uniteApprentissage.microCompetence.competence.module', function($q) use ($filiere) {
    $q->where('filiere_id', $filiere->id);
});

if ($niveauFilter !== null) {
    $dbChapitresQuery->where(function ($q) use ($niveauFilter) {
        $q->where('code', 'LIKE', "T.{$niveauFilter}%")
          ->orWhereHas('uniteApprentissage', function ($uaQuery) use ($niveauFilter) {
              $uaQuery->where('code', 'LIKE', "UA.{$niveauFilter}%");
          });
    });
}

$dbChapitres = $dbChapitresQuery->get();

foreach ($dbChapitres as $chapitre) {
    if (!empty($chapitre->reference) && !isset($csvChapitreRefs[$chapitre->reference])) {
        $obsoleteItems[] = [
            'type' => 'Chapitre (Tutoriel)',
            'id' => $chapitre->id,
            'reference' => $chapitre->reference,
            'code' => $chapitre->code,
            'nom' => $chapitre->nom,
            'model' => $chapitre
        ];
    }
}

// 5.2 Unités d'apprentissage orphelines
$dbUasQuery = UniteApprentissage::whereHas('microCompetence.competence.module', function($q) use ($filiere) {
    $q->where('filiere_id', $filiere->id);
});

if ($niveauFilter !== null) {
    $dbUasQuery->where('code', 'LIKE', "UA.{$niveauFilter}%");
}

$dbUas = $dbUasQuery->get();

foreach ($dbUas as $ua) {
    if (!empty($ua->reference) && !isset($csvUaRefs[$ua->reference])) {
        $obsoleteItems[] = [
            'type' => 'UniteApprentissage',
            'id' => $ua->id,
            'reference' => $ua->reference,
            'code' => $ua->code,
            'nom' => $ua->nom,
            'model' => $ua
        ];
    }
}

// 5.3 Alignements UA orphelins
$dbAlignementsQuery = AlignementUa::whereHas('sessionFormation', function($q) use ($filiere) {
    $q->where('filiere_id', $filiere->id);
});

if ($niveauFilter !== null) {
    $dbAlignementsQuery->whereHas('uniteApprentissage', function ($q) use ($niveauFilter) {
        $q->where('code', 'LIKE', "UA.{$niveauFilter}%");
    });
}

$dbAlignements = $dbAlignementsQuery->get();

foreach ($dbAlignements as $alignement) {
    if (!empty($alignement->reference) && !isset($csvAlignementRefs[$alignement->reference])) {
        $obsoleteItems[] = [
            'type' => 'AlignementUa',
            'id' => $alignement->id,
            'reference' => $alignement->reference,
            'code' => '',
            'nom' => "Alignement {$alignement->reference}",
            'model' => $alignement
        ];
    }
}

// 6. Génération du rapport CSV dans reports/
$reportsDir = __DIR__ . '/reports';
if (!is_dir($reportsDir)) {
    mkdir($reportsDir, 0777, true);
}

$reportFileName = $filiereCode . ($niveauFilter ? '_N' . $niveauFilter : '') . '_elements_a_supprimer.csv';
$reportFile = $reportsDir . '/' . $reportFileName;
$csvOut = fopen($reportFile, 'w');
fputcsv($csvOut, ['type', 'id', 'reference', 'code', 'nom']);

foreach ($obsoleteItems as $item) {
    fputcsv($csvOut, [
        $item['type'],
        $item['id'],
        $item['reference'],
        $item['code'],
        $item['nom']
    ]);
}
fclose($csvOut);

// 7. Affichage des résultats
echo "📋 Rapport d'orphelins généré : \033[32m$reportFile\033[0m\n";
echo "-------------------------------------------------------------------\n";
$tutoCount = count(array_filter($obsoleteItems, fn($i) => $i['type'] === 'Chapitre (Tutoriel)'));
$uaCount = count(array_filter($obsoleteItems, fn($i) => $i['type'] === 'UniteApprentissage'));
$alignCount = count(array_filter($obsoleteItems, fn($i) => $i['type'] === 'AlignementUa'));

echo "Résultat de la détection (Filière {$filiereCode}" . ($niveauFilter ? " - Niveau N{$niveauFilter}" : "") . ") :\n";
echo "  - Chapitres / Tutos orphelins en BDD : \033[1;33m$tutoCount\033[0m\n";
echo "  - Unités d'apprentissage orphelines : \033[1;33m$uaCount\033[0m\n";
echo "  - Alignements UA orphelins : \033[1;33m$alignCount\033[0m\n";
echo "  - TOTAL éléments à supprimer : \033[1;31m" . count($obsoleteItems) . "\033[0m\n";
echo "-------------------------------------------------------------------\n";

if (count($obsoleteItems) > 0) {
    echo "\nExtrait des éléments détectés (max 10 premiers) :\n";
    $preview = array_slice($obsoleteItems, 0, 10);
    foreach ($preview as $item) {
        echo "  [{$item['type']}] ID: {$item['id']} | Ref: {$item['reference']} | Nom: {$item['nom']}\n";
    }
    if (count($obsoleteItems) > 10) {
        echo "  ... et " . (count($obsoleteItems) - 10) . " autre(s) élément(s) (voir fichier CSV complet).\n";
    }
}

// 8. Suppression effective si demandé via --delete
if ($shouldDelete) {
    if (count($obsoleteItems) === 0) {
        echo "\n\033[32m✅ Aucun élément orphelin à supprimer pour ce périmètre.\033[0m\n\n";
    } else {
        echo "\n\033[31m⚠️ Exécution de la suppression en cascade en cours...\033[0m\n";
        
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Ordre de suppression sécurisé : Alignements -> Chapitres -> UAs
            $deletedAlignCount = 0;
            $deletedChapitreCount = 0;
            $deletedUaCount = 0;

            foreach ($obsoleteItems as $item) {
                if ($item['type'] === 'AlignementUa') {
                    $item['model']->delete();
                    $deletedAlignCount++;
                }
            }

            foreach ($obsoleteItems as $item) {
                if ($item['type'] === 'Chapitre (Tutoriel)') {
                    $item['model']->delete();
                    $deletedChapitreCount++;
                }
            }

            foreach ($obsoleteItems as $item) {
                if ($item['type'] === 'UniteApprentissage') {
                    $item['model']->delete();
                    $deletedUaCount++;
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            echo "\033[32m✅ Suppression en cascade terminée avec succès :\033[0m\n";
            echo "   - Alignements supprimés : $deletedAlignCount\n";
            echo "   - Chapitres / Tutos supprimés : $deletedChapitreCount\n";
            echo "   - Unités d'apprentissage supprimées : $deletedUaCount\n\n";
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            echo "\n\033[31m❌ Erreur lors de la suppression : " . $e->getMessage() . "\033[0m\n\n";
            exit(1);
        }
    }
} else {
    echo "\n\033[33mℹ️ Mode audit (lecture seule). Pour exécuter la suppression en BDD, relancez avec l'option --delete :\033[0m\n";
    $cmdNiveau = $niveauFilter ? " --niveau={$niveauFilter}" : "";
    echo "   php deploy/imports/sessions/detect_obsolete_data.php --filiere={$filiereCode}{$cmdNiveau} --delete\n\n";
}
