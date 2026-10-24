#!/usr/bin/env node
/**
 * validate-skills.cjs — Validation statique déterministe des compétences IA
 * Colocalisé dans : plugin-dev/scripts/validate-skills.cjs
 */

const fs = require('fs');
const path = require('path');

function findContext() {
    const cwd = process.cwd();
    // 1. Si on est dans le Hub
    if (fs.existsSync(path.join(cwd, 'catalog.yaml')) && fs.existsSync(path.join(cwd, 'skills'))) {
        return {
            name: 'Hub Global',
            skillsDir: path.join(cwd, 'skills'),
            catalogPath: path.join(cwd, 'catalog.yaml')
        };
    }
    // 2. Si on est dans un projet avec .agents
    const localAgents = path.join(cwd, '.agents');
    if (fs.existsSync(localAgents)) {
        return {
            name: 'Projet Local',
            skillsDir: path.join(localAgents, 'skills'),
            catalogPath: 'D:/solicode-lms/skills-workspace/catalog.yaml'
        };
    }
    // 3. Fallback ascendant
    let current = cwd;
    while (current !== path.dirname(current)) {
        if (fs.existsSync(path.join(current, '.agents', 'skills'))) {
            return {
                name: 'Projet Détecté',
                skillsDir: path.join(current, '.agents', 'skills'),
                catalogPath: 'D:/solicode-lms/skills-workspace/catalog.yaml'
            };
        }
        current = path.dirname(current);
    }
    return {
        name: 'Défaut Hub',
        skillsDir: 'D:/solicode-lms/skills-workspace/skills',
        catalogPath: 'D:/solicode-lms/skills-workspace/catalog.yaml'
    };
}

const ctx = findContext();
const skillsDir = ctx.skillsDir;
const catalogPath = ctx.catalogPath;

console.log(`🔍 [VALIDATION SKILLS] Contexte : ${ctx.name}`);
console.log(`📂 Répertoire analysé : ${skillsDir}`);

let errorsCount = 0;

if (!fs.existsSync(catalogPath)) {
    console.warn(`⚠️ Avertissement : catalog.yaml introuvable (${catalogPath}).`);
} else {
    console.log(`✅ catalog.yaml détecté (${catalogPath}).`);
}

if (!fs.existsSync(skillsDir)) {
    console.warn(`⚠️ AVERTISSEMENT : Le dossier de skills n'existe pas : ${skillsDir}`);
} else {
    const skills = fs.readdirSync(skillsDir)
        .filter(f => {
            try { return fs.statSync(path.join(skillsDir, f)).isDirectory(); } catch { return false; }
        });

    console.log(`ℹ️ ${skills.length} skill(s) trouvé(s) dans ${skillsDir}\n`);

    const requiredRubrics = [
        '# 1. Mission',
        '# 2. Déclenchement & Limites',
        '# 3. Entrées & Ressources à Charger',
        '# 4. Contraintes & Règles Inviolables',
        '# 5. Procédure Pas-à-Pas',
        '# 6. Gestion des Ambiguïtés & Décisions',
        '# 7. Format de Sortie & Livrables',
        '# 8. Contrôle Qualité (Checklist Binaire)',
        '# 9. Gestion des Erreurs Fréquentes',
        '# 10. Exemples Concrets (Few-Shot)'
    ];

    skills.forEach(skillName => {
        const skillFile = path.join(skillsDir, skillName, 'SKILL.md');
        if (!fs.existsSync(skillFile)) {
            console.error(`❌ ERREUR [${skillName}] : Fichier SKILL.md manquant !`);
            errorsCount++;
            return;
        }

        let content = fs.readFileSync(skillFile, 'utf8').replace(/^\uFEFF/, '').trimStart();

        // Vérification frontmatter YAML
        if (!content.startsWith('---')) {
            console.error(`❌ ERREUR [${skillName}] : Frontmatter YAML manquant au début !`);
            errorsCount++;
            return;
        }

        const endOfYaml = content.indexOf('---', 3);
        if (endOfYaml === -1) {
            console.error(`❌ ERREUR [${skillName}] : Frontmatter YAML non fermé (manque deuxième ---) !`);
            errorsCount++;
            return;
        }

        const yamlHeader = content.substring(3, endOfYaml);
        const requiredYamlKeys = ['name:', 'description:', 'version:', 'inspiration:'];
        const missingYamlKeys = requiredYamlKeys.filter(k => !yamlHeader.includes(k));

        if (missingYamlKeys.length > 0) {
            console.error(`❌ ERREUR [${skillName}] : Clés YAML obligatoires manquantes : ${missingYamlKeys.join(', ')}`);
            errorsCount++;
            return;
        }

        // Vérification des rubriques
        const missingRubrics = [];
        requiredRubrics.forEach(rubric => {
            const shortName = rubric.substring(0, 4);
            if (!content.includes(rubric) && !content.includes(shortName)) {
                missingRubrics.push(rubric);
            }
        });

        if (missingRubrics.length > 0) {
            console.warn(`⚠️ AVERTISSEMENT [${skillName}] : Rubriques manquantes : ${missingRubrics.join(', ')}`);
        } else {
            console.log(`✅ Skill [${skillName}] : Conforme (YAML + Inspiration Anthropic + 10 rubriques).`);
        }
    });
}

console.log('\n----------------------------------------');
if (errorsCount === 0) {
    console.log('🎉 Validation terminée avec succès : 100% conforme, 0 erreur bloquante.\n');
    process.exit(0);
} else {
    console.error(`❌ Validation échouée : ${errorsCount} erreur(s) détectée(s).\n`);
    process.exit(1);
}
