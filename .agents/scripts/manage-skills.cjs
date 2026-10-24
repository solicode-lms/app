#!/usr/bin/env node
/**
 * manage-skills.cjs — Gestionnaire d'installation sélective des compétences IA
 *
 * Permet d'amorcer un nouveau projet vierge et d'installer uniquement
 * les compétences requises sans injecter l'intégralité du Hub Global.
 *
 * Usage :
 *   node D:/solicode-lms/skills-workspace/scripts/manage-skills.cjs bootstrap [hub-path]
 *   node .agents/scripts/manage-skills.cjs list
 *   node .agents/scripts/manage-skills.cjs install [--link|--copy]
 *   node .agents/scripts/manage-skills.cjs add <skill-name> [--link|--copy]
 *   node .agents/scripts/manage-skills.cjs remove <skill-name>
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const args = process.argv.slice(2);
const command = args[0] || 'list';

// 0. Commande d'amorce d'un projet vierge (Bootstrap)
if (command === 'bootstrap' || command === 'init') {
    const targetCwd = process.cwd();
    const explicitHub = args[1];
    const hubRoot = explicitHub ? path.resolve(explicitHub) : path.resolve(__dirname, '..');
    const hubSkills = path.join(hubRoot, '.agents', 'skills');
    const hubReferences = path.join(hubRoot, 'references');

    console.log(`\n🚀 [BOOTSTRAP NOUVEAU PROJET] Initialisation dans : ${targetCwd}`);
    console.log(`📦 [SOURCE HUB GLOBAL]      : ${hubRoot}\n`);

    const targetAgents = path.join(targetCwd, '.agents');
    const targetSkills = path.join(targetAgents, 'skills');
    const targetLocal = path.join(targetAgents, 'local');
    const targetScripts = path.join(targetAgents, 'scripts');
    const targetConfigFile = path.join(targetAgents, 'skills.json');

    if (!fs.existsSync(targetSkills)) fs.mkdirSync(targetSkills, { recursive: true });
    if (!fs.existsSync(targetLocal)) fs.mkdirSync(targetLocal, { recursive: true });
    if (!fs.existsSync(targetScripts)) fs.mkdirSync(targetScripts, { recursive: true });

    // 1. Lier les références universelles
    const targetRefLink = path.join(targetAgents, 'references');
    if (!fs.existsSync(targetRefLink)) {
        try {
            if (process.platform === 'win32') {
                execSync(`cmd /c mklink /J "${targetRefLink}" "${hubReferences}"`, { stdio: 'pipe' });
            } else {
                fs.symlinkSync(hubReferences, targetRefLink, 'dir');
            }
            console.log('🔗 Jonction NTFS créée : .agents/references -> Hub');
        } catch (e) {
            console.warn('⚠️ Avertissement jonction references :', e.message);
        }
    } else {
        console.log('ℹ️ .agents/references existe déjà.');
    }

    // 2. Lier le skill project-setup comme compétence d amorce
    const targetSetupSkill = path.join(targetSkills, 'project-setup');
    const hubSetupSkill = path.join(hubSkills, 'project-setup');
    if (!fs.existsSync(targetSetupSkill)) {
        try {
            if (process.platform === 'win32') {
                execSync(`cmd /c mklink /J "${targetSetupSkill}" "${hubSetupSkill}"`, { stdio: 'pipe' });
            } else {
                fs.symlinkSync(hubSetupSkill, targetSetupSkill, 'dir');
            }
            console.log('🔗 Jonction NTFS créée : .agents/skills/project-setup -> Hub');
        } catch (e) {
            console.warn('⚠️ Avertissement jonction project-setup :', e.message);
        }
    } else {
        console.log('ℹ️ .agents/skills/project-setup existe déjà.');
    }

    // 3. Copier manage-skills.cjs
    const localScript = path.join(targetScripts, 'manage-skills.cjs');
    if (!fs.existsSync(localScript)) {
        fs.copyFileSync(__filename, localScript);
        console.log('📄 Script copié : .agents/scripts/manage-skills.cjs');
    }

    // 4. Initialiser skills.json
    if (!fs.existsSync(targetConfigFile)) {
        const initialConfig = {
            hubPath: hubRoot,
            linkMode: 'junction',
            skills: ['project-setup']
        };
        fs.writeFileSync(targetConfigFile, JSON.stringify(initialConfig, null, 2) + '\n', 'utf8');
        console.log('📝 Manifeste créé : .agents/skills.json (avec project-setup)');
    }

    console.log(`\n🎉 Socle amorcé avec succès !\n`);
    console.log('👉 Étape suivante :');
    console.log('Demandez à votre Agent IA : « Initialise l agent pour ce projet avec project-setup »');
    console.log('L agent qualifiera le projet (Dev logiciel, Tutoriels/Formation, Ateliers APC) et déploiera les skills nécessaires.\n');
    process.exit(0);
}

const rootDir = path.resolve(__dirname, '..', '..');
const agentsDir = path.join(rootDir, '.agents');
const skillsDir = path.join(agentsDir, 'skills');
const configFile = path.join(agentsDir, 'skills.json');

// 1. Charger la configuration
function loadConfig() {
    if (!fs.existsSync(configFile)) {
        console.error(`❌ Fichier de configuration introuvable : ${configFile}`);
        console.error('👉 Pour amorcer un nouveau projet, exécutez d abord :');
        console.error('   node <chemin-vers-hub>/scripts/manage-skills.cjs bootstrap\n');
        process.exit(1);
    }
    try {
        return JSON.parse(fs.readFileSync(configFile, 'utf8'));
    } catch (e) {
        console.error(`❌ Erreur de lecture dans ${configFile} :`, e.message);
        process.exit(1);
    }
}

function saveConfig(config) {
    fs.writeFileSync(configFile, JSON.stringify(config, null, 2) + '\n', 'utf8');
}

// 2. Vérifier les skills du Hub
function getHubSkills(hubPath) {
    const hubSkillsDir = path.join(hubPath, '.agents', 'skills');
    if (!fs.existsSync(hubSkillsDir)) {
        const altDir = path.join(hubPath, 'skills');
        if (fs.existsSync(altDir)) {
            return { dir: altDir, list: fs.readdirSync(altDir).filter(f => fs.statSync(path.join(altDir, f)).isDirectory()) };
        }
        return { dir: hubSkillsDir, list: [] };
    }
    const list = fs.readdirSync(hubSkillsDir).filter(f => {
        try {
            return fs.statSync(path.join(hubSkillsDir, f)).isDirectory();
        } catch {
            return false;
        }
    });
    return { dir: hubSkillsDir, list };
}

const config = loadConfig();
const hub = getHubSkills(config.hubPath);

switch (command) {
    case 'list': {
        console.log(`\n📋 [SKILLS DU PROJET] Répertoire : ${skillsDir}`);
        console.log(`📦 [HUB GLOBAL]       Répertoire : ${hub.dir}\n`);

        const installed = fs.existsSync(skillsDir)
            ? fs.readdirSync(skillsDir).filter(f => fs.statSync(path.join(skillsDir, f)).isDirectory())
            : [];

        console.log('--- Compétences configurées dans skills.json :');
        config.skills.forEach(skill => {
            const isInstalled = installed.includes(skill);
            const statusIcon = isInstalled ? '✅ Actif' : '❌ Non installé';
            console.log(`  - ${skill.padEnd(25)} [${statusIcon}]`);
        });

        const unmanaged = installed.filter(s => !config.skills.includes(s));
        if (unmanaged.length > 0) {
            console.log('\n⚠️ Compétences présentes localement mais NON déclarées dans skills.json :');
            unmanaged.forEach(skill => console.log(`  - ${skill} (à supprimer ou à ajouter)`));
        }

        if (hub.list.length > 0) {
            const excluded = hub.list.filter(s => !config.skills.includes(s));
            console.log('\n💡 Compétences du Hub exclues de ce projet :');
            excluded.forEach(skill => console.log(`  - ${skill}`));
        }
        console.log('');
        break;
    }

    case 'install': {
        const mode = args.includes('--copy') ? 'copy' : 'link';
        console.log(`\n🔄 Synchronisation des skills (Mode: ${mode === 'copy' ? 'Copie physique' : 'Jonction NTFS'})...\n`);

        if (!fs.existsSync(skillsDir)) {
            fs.mkdirSync(skillsDir, { recursive: true });
        }

        const currentlyInstalled = fs.readdirSync(skillsDir).filter(f => {
            try {
                return fs.statSync(path.join(skillsDir, f)).isDirectory();
            } catch {
                return false;
            }
        });

        // Supprimer les skills qui ne sont plus dans skills.json
        currentlyInstalled.forEach(skill => {
            if (!config.skills.includes(skill)) {
                console.log(`🗑️  Suppression du skill exclu : ${skill}`);
                const target = path.join(skillsDir, skill);
                try {
                    fs.rmSync(target, { recursive: true, force: true });
                } catch (e) {
                    console.warn(`   Avertissement : impossible de supprimer ${skill} :`, e.message);
                }
            }
        });

        // Installer les skills déclarés
        config.skills.forEach(skill => {
            const dest = path.join(skillsDir, skill);
            const src = path.join(hub.dir, skill);

            if (!fs.existsSync(dest)) {
                if (!fs.existsSync(src)) {
                    console.warn(`⚠️  Skill introuvable dans le Hub : ${skill}`);
                    return;
                }

                if (mode === 'link') {
                    try {
                        if (process.platform === 'win32') {
                            execSync(`cmd /c mklink /J "${dest}" "${src}"`, { stdio: 'pipe' });
                        } else {
                            fs.symlinkSync(src, dest, 'dir');
                        }
                        console.log(`🔗 Jonction créée pour : ${skill}`);
                    } catch (e) {
                        console.error(`❌ Échec de liaison pour ${skill} :`, e.message);
                    }
                } else {
                    fs.cpSync(src, dest, { recursive: true });
                    console.log(`📋 Copie physique créée pour : ${skill}`);
                }
            } else {
                console.log(`✅  Déjà installé : ${skill}`);
            }
        });

        console.log('\n✨ Synchronisation terminée avec succès.');
        break;
    }

    case 'add': {
        const skillName = args[1];
        if (!skillName) {
            console.error('❌ Usage : node manage-skills.cjs add <nom-du-skill>');
            process.exit(1);
        }

        if (!config.skills.includes(skillName)) {
            config.skills.push(skillName);
            saveConfig(config);
            console.log(`✅ ${skillName} ajouté à skills.json.`);
        }

        execSync(`node "${__filename}" install`, { stdio: 'inherit' });
        break;
    }

    case 'remove': {
        const skillNames = args.slice(1);
        if (skillNames.length === 0) {
            console.error('❌ Usage : node manage-skills.cjs remove <nom-du-skill> [autres-skills...]');
            process.exit(1);
        }

        skillNames.forEach(skillName => {
            config.skills = config.skills.filter(s => s !== skillName);
            const dest = path.join(skillsDir, skillName);
            if (fs.existsSync(dest)) {
                fs.rmSync(dest, { recursive: true, force: true });
                console.log(`🗑️  Dossier/Jonction supprimé(e) : ${dest}`);
            }
            console.log(`✅ ${skillName} retiré de skills.json.`);
        });

        saveConfig(config);
        break;
    }

    default:
        console.log(`Commande inconnue : ${command}`);
        console.log('Commandes disponibles : bootstrap, list, install, add, remove');
        process.exit(1);
}
