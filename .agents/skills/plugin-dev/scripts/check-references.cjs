#!/usr/bin/env node
/**
 * check-references.cjs — Détecteur de liens cassés dans les références Markdown
 * Colocalisé dans : plugin-dev/scripts/check-references.cjs
 */

const fs = require('fs');
const path = require('path');

function findReferencesDir() {
    const cwd = process.cwd();
    const localRef = path.join(cwd, '.agents', 'references');
    if (fs.existsSync(localRef)) return localRef;

    const directRef = path.join(cwd, 'references');
    if (fs.existsSync(directRef)) return directRef;

    return 'c:/AppServer/solicode-lms/.agents/references';
}

const referencesDir = findReferencesDir();
console.log('🔍 [CHECK REFERENCES] Analyse des références :', referencesDir);

let checkedFiles = 0;
let brokenLinks = 0;

function scanDir(dir) {
    if (!fs.existsSync(dir)) return;
    const items = fs.readdirSync(dir, { withFileTypes: true });
    items.forEach(item => {
        const fullPath = path.join(dir, item.name);
        if (item.isDirectory()) {
            scanDir(fullPath);
        } else if (item.name.endsWith('.md')) {
            checkedFiles++;
            const content = fs.readFileSync(fullPath, 'utf8');
            const linkRegex = /\[([^\]]+)\]\(([^)]+)\)/g;
            let match;
            while ((match = linkRegex.exec(content)) !== null) {
                const linkTarget = match[2];
                if (linkTarget.startsWith('http://') || linkTarget.startsWith('https://') || linkTarget.startsWith('#') || linkTarget.startsWith('mailto:')) {
                    continue;
                }
                const cleanTarget = linkTarget.split('#')[0];
                if (!cleanTarget) continue;

                let targetPath = path.resolve(dir, cleanTarget);
                let exists = fs.existsSync(targetPath);

                // Si introuvable directement, vérifier si c'est un skill local d'un projet consommateur (via projects.json)
                if (!exists && cleanTarget.includes('skills/')) {
                    const skillMatch = cleanTarget.match(/skills[/\\]([^/\\]+)/);
                    if (skillMatch) {
                        const skillName = skillMatch[1];
                        const projectsJsonPath = 'D:/solicode-lms/skills-workspace/projects.json';
                        if (fs.existsSync(projectsJsonPath)) {
                            try {
                                const registry = JSON.parse(fs.readFileSync(projectsJsonPath, 'utf8'));
                                for (const proj of (registry.projects || [])) {
                                    if (proj.localSkills && proj.localSkills.includes(skillName)) {
                                        const altPath = path.join(proj.path, '.agents', 'skills', skillName, 'SKILL.md');
                                        if (fs.existsSync(altPath)) {
                                            exists = true;
                                            break;
                                        }
                                    }
                                }
                            } catch (_) {}
                        }
                    }
                }

                if (!exists) {
                    const relativePath = path.relative(referencesDir, fullPath);
                    console.warn(`⚠️ Lien mort dans ${relativePath} vers ${linkTarget}`);
                    brokenLinks++;
                }
            }
        }
    });
}

scanDir(referencesDir);
console.log(`✅ ${checkedFiles} fichier(s) de référence vérifié(s). ${brokenLinks} lien(s) à vérifier.`);
process.exit(0);
