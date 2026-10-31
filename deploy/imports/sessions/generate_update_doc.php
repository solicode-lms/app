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
 *   php deploy/imports/sessions/generate_update_doc.php --filiere=DM
 */

$options = getopt("", ["filiere:", "num:", "title:"]);
$filiereInput = $options['filiere'] ?? null;
$numInput = $options['num'] ?? null;
$titleInput = $options['title'] ?? null;

if (!$filiereInput) {
    echo "\n\033[31m[ERREUR] L'argument --filiere est obligatoire.\033[0m\n";
    echo "Exemple : php deploy/imports/sessions/generate_update_doc.php --filiere=DWB [--num=17] [--title=\"...\"]\n\n";
    exit(1);
}

$filiereCode = strtoupper(trim($filiereInput));
$baseDir = dirname(__DIR__, 3);
$updatesDir = $baseDir . '/deploy/updates';

if (!is_dir($updatesDir)) {
    mkdir($updatesDir, 0777, true);
}

// 1. Détermination du numéro d'update si non spécifié
if (!$numInput) {
    $existingUpdates = glob($updatesDir . '/update_*.md');
    $maxNum = 0;
    foreach ($existingUpdates as $file) {
        if (preg_match('/update_(\d+)\.md$/', $file, $m)) {
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

// 2. Détermination du titre
$filiereLabel = (strpos($filiereCode, 'MOB') !== false || in_array($filiereCode, ['DM', 'DMB'])) ? 'Développement Mobile' : 'Développement Web';
$defaultTitle = "Mise à jour des sessions et compétences ($filiereLabel)";
$title = $titleInput ?: $defaultTitle;

// 3. Lecture du rapport d'orphelins s'il existe
$reportFile = __DIR__ . '/reports/' . $filiereCode . '_elements_a_supprimer.csv';
$deletedItems = [];
if (file_exists($reportFile) && ($handle = fopen($reportFile, 'r')) !== false) {
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

// 4. Construction du contenu Markdown
$md = "# $title\n\n";

if (!empty($deletedItems)) {
    $md .= "## 1. Données orphelines à supprimer en BDD\n\n";
    $md .= "Les éléments ci-dessous existent en base de données pour la filière **$filiereLabel** mais n'apparaissent plus dans les fichiers d'importation CSV.\n";
    $md .= "La suppression en cascade de ces enregistrements doit être effectuée via le script dédié ou les commandes ci-après.\n\n";
    
    $md .= "### Liste des éléments orphelins détectés (" . count($deletedItems) . ") :\n";
    foreach ($deletedItems as $item) {
        $md .= "- **[{$item['type']}]** ID: `{$item['id']}` | Réf: `{$item['reference']}` | {$item['nom']}\n";
    }
    $md .= "\n";

    $md .= "### Script de suppression en production (Linux) :\n";
    $md .= "```bash\n";
    $md .= "php deploy/imports/sessions/detect_obsolete_data.php --filiere=$filiereCode --delete\n";
    $md .= "```\n\n";
} else {
    $md .= "## 1. Suppressions\n\n";
    $md .= "Aucun élément orphelin à supprimer pour cette mise à jour.\n\n";
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

// 5. Écriture du fichier update_X.md
$targetFile = $updatesDir . "/update_{$updateNum}.md";
file_put_contents($targetFile, $md);

// 6. Optionnel : archiver une copie des CSV dans deploy/updates/update_{updateNum}/
$subDir = (strpos($filiereCode, 'MOB') !== false || in_array($filiereCode, ['DM', 'DMB'])) ? 'mobile' : 'web';
$sourceDir = __DIR__ . '/' . $subDir;
$archiveDir = $updatesDir . "/update_{$updateNum}";

if (is_dir($sourceDir)) {
    if (!is_dir($archiveDir)) {
        mkdir($archiveDir, 0777, true);
    }
    $csvFiles = glob($sourceDir . '/*.csv');
    foreach ($csvFiles as $f) {
        copy($f, $archiveDir . '/' . basename($f));
    }
}

echo "\n\033[32m✅ Fiche de déploiement générée avec succès :\033[0m\n";
echo "   📄 Fichier : \033[33m$targetFile\033[0m\n";
if (is_dir($archiveDir) && !empty(glob($archiveDir . '/*.csv'))) {
    echo "   📁 CSV archivés dans : \033[33m$archiveDir/\033[0m\n";
}
echo "\n";
