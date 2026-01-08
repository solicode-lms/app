/**
 * merge-md.js
 *
 * Ce script parcourt tous les dossiers situés à la racine du projet (hors "prompts" et "node_modules"),
 * fusionne les fichiers .md présents dans chacun de ces dossiers,
 * puis génère pour chaque dossier un fichier de sortie dans : /prompts/{NomDossier}.prompt.md
 * Enfin, il crée un fichier agent-soli-lms.prompt.md dans /prompts/ qui rassemble
 * l’ensemble des prompts générés dans ce dossier.
 */

const fs = require('fs');
const path = require('path');
const util = require('util');

const readdir = util.promisify(fs.readdir);
const stat = util.promisify(fs.stat);
const readFile = util.promisify(fs.readFile);
const writeFile = util.promisify(fs.writeFile);

(async () => {
  try {
    // 1. Récupérer la racine du projet et définir le dossier de sortie "prompts/"
    const projectRoot = process.cwd();
    const promptsDir = path.join(projectRoot, 'prompts');

    // 2. Vérifier/créer le dossier "prompts/"
    if (!fs.existsSync(promptsDir)) {
      fs.mkdirSync(promptsDir, { recursive: true });
    }

    // 3. Lister toutes les entrées (fichiers et dossiers) à la racine
    const entries = await readdir(projectRoot);

    // 4. Fusionner les .md dans chaque dossier racine (hors "prompts" et "node_modules")
    for (const entry of entries) {
      const fullPath = path.join(projectRoot, entry);
      const entryStat = await stat(fullPath);

      // Ne traiter que les dossiers (hors "prompts" et "node_modules")
      if (entryStat.isDirectory() && entry !== 'prompts' && entry !== 'node_modules') {
        const folderName = entry;
        const folderPath = fullPath;

        // Lire le contenu de ce dossier
        const subEntries = await readdir(folderPath);
        const mdFiles = [];

        // Filtrer les fichiers .md dans ce dossier
        for (const subEntry of subEntries) {
          const subFullPath = path.join(folderPath, subEntry);
          const subStat = await stat(subFullPath);

          if (
            subStat.isFile() &&
            path.extname(subEntry).toLowerCase() === '.md'
          ) {
            mdFiles.push(subEntry);
          }
        }

        // Si aucun fichier .md dans le dossier, passer au suivant
        if (mdFiles.length === 0) {
          console.log(`❗ Aucun .md dans "${folderName}", passage au dossier suivant.`);
          continue;
        }

        // Concaténer le contenu de chaque .md (tri alphabétique)
        mdFiles.sort((a, b) => a.localeCompare(b, 'fr', { numeric: true }));
        let combinedContent = '';

        for (const mdFile of mdFiles) {
          const mdPath = path.join(folderPath, mdFile);
          const content = await readFile(mdPath, 'utf8');

          combinedContent += `<!-- ===== ${mdFile} ===== -->\n\n`;
          combinedContent += content.trim() + '\n\n';
        }

        // Écrire le fichier fusionné dans /prompts/{NomDossier}.prompt.md
        const outputFileName = `${folderName}.prompt.md`;
        const outputPath = path.join(promptsDir, outputFileName);

        await writeFile(outputPath, combinedContent, 'utf8');
        console.log(`✔️  Généré : prompts/${outputFileName}`);
      }
    }

    console.log('🔄 Fusion terminée pour tous les dossiers individuels.');

    // 5. Rassembler tous les prompts générés dans un seul fichier agent-soli-lms.prompt.md
    const promptFiles = await readdir(promptsDir);
    const allPromptFiles = promptFiles.filter(file => file.endsWith('.prompt.md') && file !== 'agent-soli-lms.prompt.md');

    if (allPromptFiles.length === 0) {
      console.log('⚠️  Aucun fichier ".prompt.md" trouvé dans /prompts pour générer agent-soli-lms.prompt.md.');
      return;
    }

    let combinedAll = '';
    // Trier les fichiers pour un ordre stable
    allPromptFiles.sort((a, b) => a.localeCompare(b, 'fr', { numeric: true }));

    for (const promptFile of allPromptFiles) {
      const promptPath = path.join(promptsDir, promptFile);
      const content = await readFile(promptPath, 'utf8');

      combinedAll += `<!-- ===== ${promptFile} ===== -->\n\n`;
      combinedAll += content.trim() + '\n\n';
    }

    const finalOutput = path.join(promptsDir, 'agent-soli-lms.prompt.md');
    await writeFile(finalOutput, combinedAll, 'utf8');
    console.log(`✔️  Généré : prompts/agent-soli-lms.prompt.md`);

    console.log('✅ Tous les prompts ont été rassemblés dans agent-soli-lms.prompt.md.');
  } catch (err) {
    console.error('❌ Erreur lors de la fusion des fichiers .md dans les dossiers :', err);
    process.exit(1);
  }
})();
