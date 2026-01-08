/**
 * watch-md.js
 *
 * Surveille tous les fichiers *.md (hors /prompts/ et /node_modules/)
 * et lance merge-md.js à chaque changement.
 */

const fs = require('fs');
const path = require('path');
const { exec } = require('child_process');
const chokidar = require('chokidar');

(async () => {
  const projectRoot = process.cwd();

  // 1. Configuration du watcher : on observe tout, mais on ignore prompts/ et node_modules/
  const watcher = chokidar.watch('.', {
    cwd: projectRoot,
    persistent: true,
    ignoreInitial: true, // n’émet pas d’événement pour les fichiers existants au lancement
    usePolling: true,    // plus fiable sous Windows
    interval: 100,
    awaitWriteFinish: {
      stabilityThreshold: 500,
      pollInterval: 100
    },
    ignored: (filePath, stats) => {
       // Calculer le chemin relatif à projectRoot
  const rel = path.relative(projectRoot, filePath);

      // 1. Ignorer tout ce qui est dans prompts/ ou node_modules/
      if (rel.startsWith('prompts' + path.sep) || rel.startsWith('node_modules' + path.sep)) {
        return true;
      }
      // 2. Ignorer les fichiers qui ne se terminent pas par .md
      if (stats && stats.isFile() && !rel.endsWith('.md')) {
        return true;
      }
      return false;
    }
  });

  const runMerge = (filePath, eventName) => {
    console.log(`📄 [${eventName}] détecté sur : ${filePath} → exécution de merge-md…`);
    exec('npm run merge-md', (err, stdout, stderr) => {
      if (err) {
        console.error(`❌ Erreur pendant merge-md.js :`, err);
        return;
      }
      if (stderr) console.error(stderr);
      console.log(stdout);
    });
  };

  watcher
    .on('add',    file => runMerge(file, 'add'))
    .on('change', file => runMerge(file, 'change'))
    .on('unlink', file => runMerge(file, 'unlink'))
    .on('error',  error => console.error(`Watcher erreur: ${error}`))
    .on('ready',  () => console.log('🎉 Watcher prêt, surveillance des .md démarrée.'));

})();
