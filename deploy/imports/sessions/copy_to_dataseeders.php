<?php
/**
 * copy_to_dataseeders.php
 * 
 * Remplace le contenu des fichiers CSV de DataSeeder de Solicode LMS
 * (modules/PkgCompetences/Database/data/ et modules/PkgSessions/Database/data/)
 * par les seules données à importer pour la filière cible en traitement.
 * 
 * Usage:
 *   php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DWB
 *   php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DM
 */

$options = getopt("", ["filiere:", "dir:"]);
$filiereInput = $options['filiere'] ?? null;
$customDir = $options['dir'] ?? null;

if (!$filiereInput) {
    echo "\n\033[31m[ERREUR] L'argument --filiere est obligatoire.\033[0m\n";
    echo "Exemple : php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DWB\n\n";
    exit(1);
}

$filiereCode = strtoupper(trim($filiereInput));
$baseDir = dirname(__DIR__, 3); // Racine du projet Solicode LMS

if ($customDir) {
    $sourceDir = $customDir;
} else {
    $subDir = (strpos($filiereCode, 'MOB') !== false || in_array($filiereCode, ['DM', 'DMB'])) ? 'mobile' : 'web';
    $sourceDir = __DIR__ . '/' . $subDir;
}

if (!is_dir($sourceDir)) {
    echo "\n\033[31m[ERREUR] Répertoire source introuvable : $sourceDir\033[0m\n\n";
    exit(1);
}

echo "\n\033[36m===================================================================\033[0m\n";
echo "\033[1;36m  📦 MISE À JOUR DES DATASEEDERS CSV - FILIÈRE $filiereCode\033[0m\n";
echo "\033[36m===================================================================\033[0m\n";
echo "📂 Dossier source : \033[33m$sourceDir\033[0m\n\n";

// Table de correspondance [Fichier Source Possibles] => Fichier Cible Officiel
$mapping = [
    'competences' => [
        'sources' => ['competences.csv', 'competence.csv'],
        'target' => $baseDir . '/modules/PkgCompetences/Database/data/competences.csv'
    ],
    'microCompetences' => [
        'sources' => ['microCompetences.csv', 'microCompetence.csv'],
        'target' => $baseDir . '/modules/PkgCompetences/Database/data/microCompetences.csv'
    ],
    'uniteApprentissages' => [
        'sources' => ['uniteApprentissages.csv', 'uniteApprentissage.csv', 'uas.csv'],
        'target' => $baseDir . '/modules/PkgCompetences/Database/data/uniteApprentissages.csv'
    ],
    'chapitres' => [
        'sources' => ['chapitres.csv', 'chapitre.csv'],
        'target' => $baseDir . '/modules/PkgCompetences/Database/data/chapitres.csv'
    ],
    'sessionFormations' => [
        'sources' => ['sessionFormations.csv', 'sessionFormation.csv', 'sessions.csv'],
        'target' => $baseDir . '/modules/PkgSessions/Database/data/sessionFormations.csv'
    ],
    'alignementUas' => [
        'sources' => ['alignementUas.csv', 'alignementUa.csv'],
        'target' => $baseDir . '/modules/PkgSessions/Database/data/alignementUas.csv'
    ],
];

$successCount = 0;
$missingCount = 0;

foreach ($mapping as $key => $config) {
    $foundSource = null;
    foreach ($config['sources'] as $srcCandidate) {
        $fullPath = $sourceDir . '/' . $srcCandidate;
        if (file_exists($fullPath)) {
            $foundSource = $fullPath;
            break;
        }
    }

    $targetPath = $config['target'];
    $targetDir = dirname($targetPath);
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (!$foundSource) {
        echo "⚠️ \033[33m[IGNORÉ]\033[0m " . basename($targetPath) . " : Fichier source introuvable dans $sourceDir\n";
        $missingCount++;
        continue;
    }

    // Compter le nombre de lignes dans le fichier source
    $lineCount = count(file($foundSource));
    $dataRowCount = max(0, $lineCount - 1); // Déduire l'en-tête

    // Remplacement direct du fichier DataSeeder cible
    if (copy($foundSource, $targetPath)) {
        echo "✅ \033[32m[COPIÉ]\033[0m " . basename($targetPath) . " ➔ $dataRowCount ligne(s) de données depuis " . basename($foundSource) . "\n";
        $successCount++;
    } else {
        echo "❌ \033[31m[ÉCHEC]\033[0m Impossible d'écraser " . basename($targetPath) . "\n";
    }
}

echo "\n-------------------------------------------------------------------\n";
echo "Bilan : \033[32m$successCount fichier(s) mis à jour\033[0m, \033[33m$missingCount manquant(s)\033[0m.\n";
if ($successCount > 0) {
    echo "\n\033[32mPrêt pour l'exécution des seeders :\033[0m\n";
    echo "  php artisan db:seed --class=\"Modules\PkgCompetences\Database\Seeders\CompetenceSeeder\"\n";
    echo "  php artisan db:seed --class=\"Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder\"\n";
    echo "  php artisan db:seed --class=\"Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder\"\n";
    echo "  php artisan db:seed --class=\"Modules\PkgCompetences\Database\Seeders\ChapitreSeeder\"\n";
    echo "  php artisan db:seed --class=\"Modules\PkgSessions\Database\Seeders\SessionFormationSeeder\"\n";
    echo "  php artisan db:seed --class=\"Modules\PkgSessions\Database\Seeders\AlignementUaSeeder\"\n\n";
}
