#!/usr/bin/env node
/**
 * =====================================================================
 * deploy-update.js - Script de déploiement des mises à jour Solicode LMS
 * =====================================================================
 *
 * Usage :
 *   node deploy-update.js --version 7
 *   node deploy-update.js --version 7 --dry-run
 *   node deploy-update.js --version 7 --help
 *
 * Ce script :
 *   1. Lit le dossier docs/8.déploiement/update_N/
 *   2. Pour chaque fichier CSV trouvé, le copie dans le dossier data/ du module correspondant
 *   3. Affiche les commandes php artisan db:seed à exécuter (Windows + Linux/Serveur)
 */

const fs = require('fs');
const path = require('path');

// ─────────────────────────────────────────────────────────────
//  CONFIGURATION : Mapping nom de fichier CSV → chemin data/ dans les modules
// ─────────────────────────────────────────────────────────────
const CSV_MAPPING = {
  // PkgCompetences
  'competences.csv':        'modules/PkgCompetences/Database/data/competences.csv',
  'microCompetences.csv':   'modules/PkgCompetences/Database/data/microCompetences.csv',
  'uniteApprentissages.csv':'modules/PkgCompetences/Database/data/uniteApprentissages.csv',
  'chapitres.csv':          'modules/PkgCompetences/Database/data/chapitres.csv',
  'phaseEvaluations.csv':   'modules/PkgCompetences/Database/data/phaseEvaluations.csv',
  'critereEvaluations.csv': 'modules/PkgCompetences/Database/data/critereEvaluations.csv',

  // PkgSessions
  'sessionFormation.csv':   'modules/PkgSessions/Database/data/sessionFormations.csv',
  'sessionFormations.csv':  'modules/PkgSessions/Database/data/sessionFormations.csv',
  'alignementUa.csv':       'modules/PkgSessions/Database/data/alignementUas.csv',
  'alignementUas.csv':      'modules/PkgSessions/Database/data/alignementUas.csv',
  'livrableSessions.csv':   'modules/PkgSessions/Database/data/livrableSessions.csv',

  // PkgFormation
  'filieres.csv':           'modules/PkgFormation/Database/data/filieres.csv',
  'specialites.csv':        'modules/PkgFormation/Database/data/specialites.csv',
  'formateurs.csv':         'modules/PkgFormation/Database/data/formateurs.csv',
  'modules.csv':            'modules/PkgFormation/Database/data/modules.csv',
  'anneeFormations.csv':    'modules/PkgFormation/Database/data/anneeFormations.csv',

  // PkgCreationProjet
  'livrables.csv':          'modules/PkgCreationProjet/Database/data/livrables.csv',
  'labelProjets.csv':       'modules/PkgCreationProjet/Database/data/labelProjets.csv',
  'mobilisationUas.csv':    'modules/PkgCreationProjet/Database/data/mobilisationUas.csv',

  // PkgCreationTache
  'taches.csv':             'modules/PkgCreationTache/Database/data/taches.csv',
  'phaseProjets.csv':       'modules/PkgCreationTache/Database/data/phaseProjets.csv',
  'prioriteTaches.csv':     'modules/PkgCreationTache/Database/data/prioriteTaches.csv',

  // PkgRealisationProjets
  'affectationProjets.csv': 'modules/PkgRealisationProjets/Database/data/affectationProjets.csv',
  'realisationProjets.csv': 'modules/PkgRealisationProjets/Database/data/realisationProjets.csv',
  'workflowProjets.csv':    'modules/PkgRealisationProjets/Database/data/workflowProjets.csv',
  'etatsRealisationProjets.csv': 'modules/PkgRealisationProjets/Database/data/etatsRealisationProjets.csv',
  'validations.csv':        'modules/PkgRealisationProjets/Database/data/validations.csv',
  'livrablesRealisations.csv': 'modules/PkgRealisationProjets/Database/data/livrablesRealisations.csv',
};

// ─────────────────────────────────────────────────────────────
//  MAPPING Fichier CSV → Classe Seeder Laravel
// ─────────────────────────────────────────────────────────────
const SEEDER_MAPPING = {
  'competences.csv':         'Modules\\PkgCompetences\\Database\\Seeders\\CompetenceSeeder',
  'microCompetences.csv':    'Modules\\PkgCompetences\\Database\\Seeders\\MicroCompetenceSeeder',
  'uniteApprentissages.csv': 'Modules\\PkgCompetences\\Database\\Seeders\\UniteApprentissageSeeder',
  'chapitres.csv':           'Modules\\PkgCompetences\\Database\\Seeders\\ChapitreSeeder',
  'phaseEvaluations.csv':    'Modules\\PkgCompetences\\Database\\Seeders\\PhaseEvaluationSeeder',
  'critereEvaluations.csv':  'Modules\\PkgCompetences\\Database\\Seeders\\CritereEvaluationSeeder',

  'sessionFormation.csv':    'Modules\\PkgSessions\\Database\\Seeders\\SessionFormationSeeder',
  'sessionFormations.csv':   'Modules\\PkgSessions\\Database\\Seeders\\SessionFormationSeeder',
  'alignementUa.csv':        'Modules\\PkgSessions\\Database\\Seeders\\AlignementUaSeeder',
  'alignementUas.csv':       'Modules\\PkgSessions\\Database\\Seeders\\AlignementUaSeeder',
  'livrableSessions.csv':    'Modules\\PkgSessions\\Database\\Seeders\\LivrableSessionSeeder',

  'filieres.csv':            'Modules\\PkgFormation\\Database\\Seeders\\FiliereSeeder',
  'specialites.csv':         'Modules\\PkgFormation\\Database\\Seeders\\SpecialiteSeeder',
  'formateurs.csv':          'Modules\\PkgFormation\\Database\\Seeders\\FormateurSeeder',
  'modules.csv':             'Modules\\PkgFormation\\Database\\Seeders\\ModuleSeeder',
  'anneeFormations.csv':     'Modules\\PkgFormation\\Database\\Seeders\\AnneeFormationSeeder',

  'livrables.csv':           'Modules\\PkgCreationProjet\\Database\\Seeders\\LivrableSeeder',
  'labelProjets.csv':        'Modules\\PkgCreationProjet\\Database\\Seeders\\LabelProjetSeeder',
  'mobilisationUas.csv':     'Modules\\PkgCreationProjet\\Database\\Seeders\\MobilisationUaSeeder',

  'taches.csv':              'Modules\\PkgCreationTache\\Database\\Seeders\\TacheSeeder',
  'phaseProjets.csv':        'Modules\\PkgCreationTache\\Database\\Seeders\\PhaseProjetSeeder',
  'prioriteTaches.csv':      'Modules\\PkgCreationTache\\Database\\Seeders\\PrioriteTacheSeeder',

  'affectationProjets.csv':  'Modules\\PkgRealisationProjets\\Database\\Seeders\\AffectationProjetSeeder',
  'realisationProjets.csv':  'Modules\\PkgRealisationProjets\\Database\\Seeders\\RealisationProjetSeeder',
  'workflowProjets.csv':     'Modules\\PkgRealisationProjets\\Database\\Seeders\\WorkflowProjetSeeder',
  'etatsRealisationProjets.csv': 'Modules\\PkgRealisationProjets\\Database\\Seeders\\EtatsRealisationProjetSeeder',
  'validations.csv':         'Modules\\PkgRealisationProjets\\Database\\Seeders\\ValidationSeeder',
  'livrablesRealisations.csv': 'Modules\\PkgRealisationProjets\\Database\\Seeders\\LivrablesRealisationSeeder',
};

// ─────────────────────────────────────────────────────────────
//  COULEURS ANSI pour le terminal
// ─────────────────────────────────────────────────────────────
const COLOR = {
  reset:  '\x1b[0m',
  bold:   '\x1b[1m',
  green:  '\x1b[32m',
  yellow: '\x1b[33m',
  blue:   '\x1b[34m',
  cyan:   '\x1b[36m',
  red:    '\x1b[31m',
  gray:   '\x1b[90m',
};

function log(msg)      { console.log(msg); }
function logOk(msg)    { console.log(`${COLOR.green}✓${COLOR.reset} ${msg}`); }
function logWarn(msg)  { console.log(`${COLOR.yellow}⚠${COLOR.reset}  ${msg}`); }
function logError(msg) { console.error(`${COLOR.red}✗${COLOR.reset} ${msg}`); }
function logInfo(msg)  { console.log(`${COLOR.cyan}ℹ${COLOR.reset}  ${msg}`); }
function logTitle(msg) { console.log(`\n${COLOR.bold}${COLOR.blue}${msg}${COLOR.reset}`); }
function logSep()      { console.log(`${COLOR.gray}${'─'.repeat(60)}${COLOR.reset}`); }

// ─────────────────────────────────────────────────────────────
//  PARSE DES ARGUMENTS
// ─────────────────────────────────────────────────────────────
function parseArgs() {
  const args = process.argv.slice(2);
  const opts = { version: null, dryRun: false, help: false, appRoot: null };

  for (let i = 0; i < args.length; i++) {
    if (args[i] === '--version' || args[i] === '-v') {
      opts.version = args[++i];
    } else if (args[i] === '--dry-run') {
      opts.dryRun = true;
    } else if (args[i] === '--help' || args[i] === '-h') {
      opts.help = true;
    } else if (args[i] === '--app-root') {
      opts.appRoot = args[++i];
    }
  }
  return opts;
}

// ─────────────────────────────────────────────────────────────
//  AIDE
// ─────────────────────────────────────────────────────────────
function printHelp() {
  log(`
${COLOR.bold}deploy-update.js${COLOR.reset} — Déploiement des mises à jour CSV de Solicode LMS

${COLOR.bold}Usage :${COLOR.reset}
  node deploy-update.js --version <N> [options]

${COLOR.bold}Options :${COLOR.reset}
  --version, -v <N>    Numéro de version de la mise à jour (ex: 7, 8)
  --dry-run            Simuler sans copier les fichiers
  --app-root <path>    Chemin racine de l'application (défaut: répertoire courant)
  --help, -h           Afficher cette aide

${COLOR.bold}Exemples :${COLOR.reset}
  node deploy-update.js --version 7
  node deploy-update.js --version 8 --dry-run
  node deploy-update.js --version 7 --app-root D:\\solicode-lms\\app

${COLOR.bold}Ce que fait ce script :${COLOR.reset}
  1. Lit les fichiers CSV depuis  docs/8.deploiement/update_N/
  2. Copie chaque CSV vers le dossier data/ du module correspondant
  3. Affiche les commandes php artisan db:seed (Windows & Linux/Serveur)
`);
}

// ─────────────────────────────────────────────────────────────
//  DÉTECTION AUTOMATIQUE DE LA RACINE
// ─────────────────────────────────────────────────────────────
function detectAppRoot(startDir) {
  let dir = startDir;
  for (let i = 0; i < 6; i++) {
    if (fs.existsSync(path.join(dir, 'artisan'))) {
      return dir;
    }
    const parent = path.dirname(dir);
    if (parent === dir) break;
    dir = parent;
  }
  return startDir;
}

// ─────────────────────────────────────────────────────────────
//  MAIN
// ─────────────────────────────────────────────────────────────
function main() {
  const opts = parseArgs();

  if (opts.help) {
    printHelp();
    process.exit(0);
  }

  if (!opts.version) {
    logError('Le numéro de version est requis. Utilisez --version N');
    logInfo('Exemple : node deploy-update.js --version 7');
    process.exit(1);
  }

  const version = opts.version;
  const appRoot = opts.appRoot
    ? path.resolve(opts.appRoot)
    : detectAppRoot(process.cwd());

  logTitle(`═══════════════════════════════════════════════════════════`);
  logTitle(`  Solicode LMS — Déploiement Update v${version}`);
  logTitle(`═══════════════════════════════════════════════════════════`);
  logInfo(`Racine du projet : ${appRoot}`);
  if (opts.dryRun) logWarn('Mode DRY-RUN activé — aucune modification ne sera effectuée');

  // ── 1. Vérification du dossier source update_N ──
  // Essayer avec accent d'abord, puis sans
  let updateDir = path.join(appRoot, 'docs', '8.déploiement', `update_${version}`);
  if (!fs.existsSync(updateDir)) {
    updateDir = path.join(appRoot, 'docs', '8.deploiement', `update_${version}`);
  }

  if (!fs.existsSync(updateDir)) {
    logError(`Dossier introuvable : update_${version}/`);
    logInfo(`Chemins recherchés :`);
    logInfo(`  docs/8.déploiement/update_${version}/`);
    logInfo(`  docs/8.deploiement/update_${version}/`);
    process.exit(1);
  }

  logOk(`Dossier source trouvé : ${updateDir}`);

  // ── 2. Lecture des fichiers CSV dans le dossier update_N ──
  const csvFiles = fs.readdirSync(updateDir).filter(f => f.endsWith('.csv'));

  if (csvFiles.length === 0) {
    logWarn(`Aucun fichier CSV trouvé dans update_${version}/`);
    process.exit(0);
  }

  logInfo(`${csvFiles.length} fichier(s) CSV trouvé(s) : ${csvFiles.join(', ')}`);
  logSep();

  // ── 3. Copie des CSV vers les dossiers data/ ──
  logTitle(`📁  Copie des fichiers CSV`);
  const copiedFiles = [];
  const unknownFiles = [];

  for (const csvFile of csvFiles) {
    const srcPath = path.join(updateDir, csvFile);
    const destRelative = CSV_MAPPING[csvFile];

    if (!destRelative) {
      unknownFiles.push(csvFile);
      logWarn(`Mapping inconnu pour : ${csvFile} (fichier ignoré)`);
      continue;
    }

    const destPath = path.join(appRoot, destRelative);
    const destDir = path.dirname(destPath);

    if (opts.dryRun) {
      logInfo(`[DRY-RUN] ${csvFile}  →  ${destRelative}`);
      copiedFiles.push(csvFile);
    } else {
      // Créer le dossier si nécessaire
      if (!fs.existsSync(destDir)) {
        fs.mkdirSync(destDir, { recursive: true });
      }

      // Sauvegarde de l'ancien fichier (seulement si différent)
      if (fs.existsSync(destPath)) {
        const prevVersion = parseInt(version) - 1;
        const backupName = path.basename(destPath, '.csv') + `_backup_v${prevVersion}.csv`;
        const backupPath = path.join(destDir, backupName);
        if (!fs.existsSync(backupPath)) {
          fs.copyFileSync(destPath, backupPath);
          logInfo(`  Sauvegarde → ${backupName}`);
        }
      }

      fs.copyFileSync(srcPath, destPath);
      logOk(`${csvFile}  →  ${destRelative}`);
      copiedFiles.push(csvFile);
    }
  }

  if (unknownFiles.length > 0) {
    logSep();
    logWarn(`Fichiers non mappés (ajoutez-les dans CSV_MAPPING du script) :`);
    unknownFiles.forEach(f => logWarn(`  - ${f}`));
  }

  // ── 4. Génération des commandes Seeder ──
  logSep();
  logTitle(`⚡  Commandes à exécuter`);

  const seedersToRun = [];
  for (const csvFile of copiedFiles) {
    const seeder = SEEDER_MAPPING[csvFile];
    if (seeder && !seedersToRun.includes(seeder)) {
      seedersToRun.push(seeder);
    }
  }

  if (seedersToRun.length === 0) {
    logWarn('Aucun seeder connu pour les fichiers copiés.');
  } else {
    // ── Windows (PowerShell / CMD) ──
    log(`\n${COLOR.bold}${COLOR.yellow}▶ Windows (PowerShell / CMD) :${COLOR.reset}`);
    for (const seeder of seedersToRun) {
      log(`  php artisan db:seed --class=${seeder}`);
    }

    // ── Linux / Serveur (avec sudo) ──
    log(`\n${COLOR.bold}${COLOR.yellow}▶ Linux / Serveur (avec sudo) :${COLOR.reset}`);
    log('```');
    for (const seeder of seedersToRun) {
      const seederLinux = seeder.replace(/\\/g, '\\\\');
      log(`  sudo php artisan db:seed --class=${seederLinux}`);
    }
    log('```');
  }

  // ── Résumé final ──
  logSep();
  logTitle(`✅  Résumé Update v${version}`);
  logOk(`${copiedFiles.length} fichier(s) CSV ${opts.dryRun ? 'simulé(s)' : 'copié(s)'}`);
  logOk(`${seedersToRun.length} seeder(s) à exécuter`);
  if (unknownFiles.length > 0) {
    logWarn(`${unknownFiles.length} fichier(s) ignoré(s) (mapping manquant)`);
  }
  if (opts.dryRun) {
    logWarn('Mode DRY-RUN : aucune modification réelle effectuée');
    logInfo('Relancez sans --dry-run pour appliquer les changements.');
  }
  log('');
}

main();
