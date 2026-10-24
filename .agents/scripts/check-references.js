/**
 * check-references.js — Détecteur de liens cassés dans les références Markdown
 */

const fs = require('fs');
const path = require('path');

const rootDir = path.resolve(__dirname, '..');
const referencesDir = path.join(rootDir, 'references');

console.log('🔍 [CHECK REFERENCES] Analyse des références :', referencesDir);

let checkedFiles = 0;

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
            // Recherche basique de liens markdown relatifs [text](file.md)
            const matches = content.matchAll(/\[([^\]]+)\]\(([^)]+\.md)\)/g);
            for (const match of matches) {
                const targetLink = match[2];
                if (!targetLink.startsWith('http') && !targetLink.startsWith('#')) {
                    const resolvedTarget = path.resolve(path.dirname(fullPath), targetLink);
                    if (!fs.existsSync(resolvedTarget)) {
                        console.warn(`⚠️ Lien mort dans ${path.relative(rootDir, fullPath)} vers ${targetLink}`);
                    }
                }
            }
        }
    });
}

scanDir(referencesDir);
console.log(`✅ ${checkedFiles} fichier(s) de référence vérifié(s). Aucun blocage.`);
process.exit(0);
