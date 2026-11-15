<?php
/**
 * generate_update_doc.php
 * 
 * Génère automatiquement la fiche de déploiement deploy/updates/update_X.md
 * récapitulant les suppressions d'éléments orphelins et les commandes d'exécution
 * des 6 seeders pour l'environnement local (Windows) et la production (Linux).
 * 
 * Usage:
 *   php deploy/imports/sessions/generate_update_doc.php --filiere=DWB --num=17 --title="Mise à jour sessions Web S3 à S7"
 *   php deploy/imports/sessions/generate_update_doc.php --filiere=DMB [--niveau=3]
 */

$options = getopt("", ["filiere:", "num:", "title:", "niveau:"]);
$filiereInput = $options['filiere'] ?? null;
$numInput = $options['num'] ?? null;
$titleInput = $options['title'] ?? null;
$niveauInput = $options['niveau'] ?? null;

if (!$filiereInput) {
    echo "\n\033[31m[ERREUR] L'argument --filiere est obligatoire.\033[0m\n";
    echo "Exemple : php deploy/imports/sessions/generate_update_doc.php --filiere=DMB [--niveau=3] [--num=18] [--title=\"...\"]\n\n";
    exit(1);
}

$filiereCode = strtoupper(trim($filiereInput));
$baseDir = dirname(__DIR__, 3);
$updatesDir = $baseDir . '/deploy/updates';

if (!is_dir($updatesDir)) {
    mkdir($updatesDir, 0777, true);
}

// 1. Détermination du répertoire source des CSV et du niveau
$subDir = (strpos($filiereCode, 'MOB') !== false || in_array($filiereCode, ['DM', 'DMB'])) ? 'mobile' : 'web';
$sourceDir = __DIR__ . '/' . $subDir;

$niveauFilter = null;
if ($niveauInput !== null) {
    $cleaned = preg_replace('/[^0-9]/', '', $niveauInput);
    if ($cleaned !== '') {
        $niveauFilter = $cleaned;
    }
} else {
    // Détection automatique du niveau depuis les CSV
    $files = ['chapitres.csv', 'chapitre.csv', 'uniteApprentissages.csv', 'uniteApprentissage.csv', 'microCompetences.csv', 'competences.csv'];
    $levelsFound = [];
    foreach ($files as $name) {
        $p = $sourceDir . '/' . $name;
        if (!file_exists($p)) continue;
        if (($handle = fopen($p, 'r')) !== false) {
            $headers = fgetcsv($handle);
            if ($headers) {
                $codeIdx = array_search('code', $headers);
                if ($codeIdx !== false) {
                    while (($row = fgetcsv($handle)) !== false) {
                        if (isset($row[$codeIdx])) {
                            $code = trim($row[$codeIdx]);
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
    if (!empty($levelsFound)) {
        arsort($levelsFound);
        $niveauFilter = (string)array_key_first($levelsFound);
    }
}

$today = date('Y-m-d');

// 2. Détermination du fichier d'update (par numéro et par date)
$existingUpdates = glob($updatesDir . '/update_*.md');
$existingTodayFile = null;

// Vérifier si un fichier d'update pour aujourd'hui existe déjà (ex: update_*_2026-11-15.md)
foreach ($existingUpdates as $file) {
    if (preg_match('/update_(\d+)_' . preg_quote($today, '/') . '\.md$/', basename($file), $m)) {
        $existingTodayFile = $file;
        $updateNum = (int)$m[1];
        break;
    }
}

if ($existingTodayFile && !$numInput) {
    $targetFile = $existingTodayFile;
    echo "\nℹ️  Fiche de déploiement existante détectée pour la date du jour ({$today}) : \033[33m" . basename($targetFile) . "\033[0m\n";
    echo "   Mise à jour du contenu existant (pas de création d'un nouveau numéro inutile).\n";
} else {
    if (!$numInput) {
        $maxNum = 0;
        foreach ($existingUpdates as $file) {
            if (preg_match('/update_(\d+)(?:_.*)?\.md$/', basename($file), $m)) {
                $n = (int)$m[1];
                if ($n > $maxNum) {
                    $maxNum = $n;
                }
            }
        }
        $updateNum = $maxNum + 1;
    } else {
        $updateNum = (int)$numInput;
    }
    $targetFile = $updatesDir . "/update_{$updateNum}_{$today}.md";
}

// 3. Détermination du titre
$filiereLabel = (strpos($filiereCode, 'MOB') !== false || in_array($filiereCode, ['DM', 'DMB'])) ? 'Développement Mobile' : 'Développement Web';
$niveauSuffix = $niveauFilter ? " - Niveau N{$niveauFilter}" : "";
$defaultTitle = "Mise à jour des sessions et compétences ({$filiereLabel}{$niveauSuffix})";
$title = $titleInput ?: $defaultTitle;

// 4. Lecture du rapport d'orphelins s'il existe
$reportFileCandidates = [];
if ($niveauFilter) {
    $reportFileCandidates[] = __DIR__ . '/reports/' . $filiereCode . '_N' . $niveauFilter . '_elements_a_supprimer.csv';
}
$reportFileCandidates[] = __DIR__ . '/reports/' . $filiereCode . '_elements_a_supprimer.csv';

$reportFile = null;
foreach ($reportFileCandidates as $candidate) {
    if (file_exists($candidate)) {
        $reportFile = $candidate;
        break;
    }
}

$deletedItems = [];
if ($reportFile && ($handle = fopen($reportFile, 'r')) !== false) {
    $headers = fgetcsv($handle);
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) >= 5) {
            $deletedItems[] = [
                'type' => $row[0],
                'id' => $row[1],
                'reference' => $row[2],
                'code' => $row[3],
                'nom' => $row[4]
            ];
        }
    }
    fclose($handle);
}

// 5. Construction du contenu Markdown
$md = "# $title\n\n";

if (!empty($deletedItems)) {
    $md .= "## 1. Données orphelines à supprimer en BDD\n\n";
    $md .= "Les éléments ci-dessous existent en base de données pour la filière **$filiereLabel**" . ($niveauFilter ? " (Niveau **N{$niveauFilter}**)" : "") . " mais n'apparaissent plus dans les fichiers d'importation CSV.\n";
    $md .= "La suppression en cascade de ces enregistrements doit être effectuée via le script dédié ou les commandes ci-après.\n\n";
    
    $md .= "### Liste des éléments orphelins détectés (" . count($deletedItems) . ") :\n";
    foreach ($deletedItems as $item) {
        $md .= "- **[{$item['type']}]** ID: `{$item['id']}` | Réf: `{$item['reference']}` | {$item['nom']}\n";
    }
    $md .= "\n";

    $cmdNiveau = $niveauFilter ? " --niveau={$niveauFilter}" : "";
    $md .= "### Script de suppression en production (Linux) :\n";
    $md .= "```bash\n";
    $md .= "php deploy/imports/sessions/detect_obsolete_data.php --filiere={$filiereCode}{$cmdNiveau} --delete\n";
    $md .= "```\n\n";
} else {
    $md .= "## 1. Suppressions\n\n";
    $md .= "Aucun élément orphelin à supprimer pour cette mise à jour" . ($niveauFilter ? " (Niveau N{$niveauFilter})" : "") . ".\n\n";
}

$md .= "## 2. Exécution des Seeders\n\n";
$md .= "### En local (Windows)\n";
$md .= "```bash\n";
$md .= "php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\CompetenceSeeder\"\n";
$md .= "php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\MicroCompetenceSeeder\"\n";
$md .= "php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\UniteApprentissageSeeder\"\n";
$md .= "php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\ChapitreSeeder\"\n";
$md .= "php artisan db:seed --class=\"Modules\\PkgSessions\\Database\\Seeders\\SessionFormationSeeder\"\n";
$md .= "php artisan db:seed --class=\"Modules\\PkgSessions\\Database\\Seeders\\AlignementUaSeeder\"\n";
$md .= "```\n\n";

$md .= "### En production (Serveur Linux)\n";
$md .= "```bash\n";
$md .= "sudo php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\CompetenceSeeder\"\n";
$md .= "sudo php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\MicroCompetenceSeeder\"\n";
$md .= "sudo php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\UniteApprentissageSeeder\"\n";
$md .= "sudo php artisan db:seed --class=\"Modules\\PkgCompetences\\Database\\Seeders\\ChapitreSeeder\"\n";
$md .= "sudo php artisan db:seed --class=\"Modules\\PkgSessions\\Database\\Seeders\\SessionFormationSeeder\"\n";
$md .= "sudo php artisan db:seed --class=\"Modules\\PkgSessions\\Database\\Seeders\\AlignementUaSeeder\"\n";
$md .= "```\n";

// 6. Écriture du fichier update_X.md
file_put_contents($targetFile, $md);

echo "\n\033[32m✅ Fiche de déploiement générée avec succès :\033[0m\n";
echo "   📄 Fichier : \033[33m$targetFile\033[0m\n";
if ($niveauFilter) {
    echo "   🎯 Niveau : \033[1;32mN{$niveauFilter}\033[0m\n";
}
echo "\n";
