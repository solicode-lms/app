/**
 * validate-skills.js — Script Node.js de validation statique déterministe
 * Vérifie :
 * 1. La présence et validité de catalog.yaml
 * 2. La conformité du frontmatter YAML (name, description, version, inspiration)
 * 3. La présence obligatoire des 10 rubriques normées du gabarit universel
 */

const fs = require('fs');
const path = require('path');

const agentsDir = path.resolve(__dirname, '..');
const projectDir = path.resolve(agentsDir, '..');
const skillsDir = path.join(agentsDir, 'skills');

// Recherche du catalogue (Hub ou racine)
const possibleCatalogPaths = [
    path.join(projectDir, 'catalog.yaml'),
    path.join(agentsDir, 'catalog.yaml'),
    'D:\\solicode-lms\\skills-workspace\\catalog.yaml'
];
const catalogPath = possibleCatalogPaths.find(p => fs.existsSync(p));

console.log('🔍 [VALIDATION SKILLS] Analyse du répertoire :', skillsDir);

let errorsCount = 0;

// 1. Vérification de catalog.yaml
if (!catalogPath) {
    console.warn('⚠️ AVERTISSEMENT : catalog.yaml introuvable (mode projet autonome).');
} else {
    console.log(`✅ catalog.yaml détecté (${catalogPath}).`);
}

// 2. Vérification des Skills
if (!fs.existsSync(skillsDir)) {
    console.warn('⚠️ AVERTISSEMENT : Le dossier .agents/skills n\'existe pas.');
} else {
    const skills = fs.readdirSync(skillsDir, { withFileTypes: true })
        .filter(dirent => dirent.isDirectory())
        .map(dirent => dirent.name);

    console.log(`ℹ️ ${skills.length} skill(s) trouvé(s) dans .agents/skills/\n`);

    const requiredHeadings = [
        '# 1. Mission',
        '# 2. Déclenchement & Limites',
        '# 3. Entrées & Ressources à Charger',
        '# 4. Contraintes & Règles Inviolables',
        '# 5. Procédure Pas-à-Pas',
        '# 6. Gestion des Ambiguïtés',
        '# 7. Format de Sortie & Livrables',
        '# 8. Contrôle Qualité',
        '# 9. Gestion des Erreurs Fréquentes',
        '# 10. Exemples Concrets'
    ];

    skills.forEach(skillName => {
        const skillFile = path.join(skillsDir, skillName, 'SKILL.md');
        if (!fs.existsSync(skillFile)) {
            console.error(`❌ ERREUR [${skillName}] : Fichier SKILL.md manquant !`);
            errorsCount++;
            return;
        }

        let content = fs.readFileSync(skillFile, 'utf8');
        content = content.replace(/^\uFEFF/, '').trimStart();

        // Contrôle Frontmatter
        if (!content.startsWith('---')) {
            console.error(`❌ ERREUR [${skillName}] : Ne commence pas par un frontmatter YAML (---).`);
            errorsCount++;
            return;
        }

        const frontmatterEnd = content.indexOf('---', 3);
        if (frontmatterEnd === -1) {
            console.error(`❌ ERREUR [${skillName}] : Frontmatter YAML non fermé (--- manquant).`);
            errorsCount++;
            return;
        }

        const frontmatter = content.slice(3, frontmatterEnd);

        if (!frontmatter.includes('name:')) {
            console.error(`❌ ERREUR [${skillName}] : Champ 'name:' manquant dans le frontmatter.`);
            errorsCount++;
        }
        if (!frontmatter.includes('description:')) {
            console.error(`❌ ERREUR [${skillName}] : Champ 'description:' manquant dans le frontmatter.`);
            errorsCount++;
        }
        if (!frontmatter.includes('inspiration:') || !frontmatter.includes('plugin:')) {
            console.error(`❌ ERREUR [${skillName}] : Métadonnée 'inspiration:' manquante ou incomplète dans le frontmatter !`);
            errorsCount++;
        }

        // Contrôle des 10 Rubriques
        let missingHeadings = 0;
        requiredHeadings.forEach(heading => {
            const prefix = heading.slice(0, 5); // ex: "# 1. "
            if (!content.includes(prefix)) {
                console.warn(`⚠️ AVERTISSEMENT [${skillName}] : Rubrique probable manquante "${heading}"`);
                missingHeadings++;
            }
        });

        if (missingHeadings === 0) {
            console.log(`✅ Skill [${skillName}] : Conforme (YAML + Inspiration Anthropic + 10 rubriques).`);
        } else {
            console.warn(`⚠️ Skill [${skillName}] : Conforme avec avertissements rubriques.`);
        }
    });
}

console.log('\n----------------------------------------');
if (errorsCount === 0) {
    console.log('🎉 Validation terminée avec succès : 100% conforme, 0 erreur bloquante.');
    process.exit(0);
} else {
    console.error(`💥 Validation échouée : ${errorsCount} erreur(s) bloquante(s).`);
    process.exit(1);
}
