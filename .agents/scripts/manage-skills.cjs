#!/usr/bin/env node
/**
 * manage-skills.cjs — Gestionnaire Granulaire des Compétences & Dépendances IA
 *
 * Architecture :
 * - Local par défaut (Dossier physique dans .agents/skills/)
 * - Global par lien symbolique (Jonction NTFS vers skills-workspace)
 * - Promotion dépendancielle en cascade (promote)
 * - Incubation / détachement local (localize)
 *
 * Usage :
 *   node .agents/scripts/manage-skills.cjs list
 *   node .agents/scripts/manage-skills.cjs install
 *   node .agents/scripts/manage-skills.cjs promote <skill-name>
 *   node .agents/scripts/manage-skills.cjs localize <skill-name>
 *   node .agents/scripts/manage-skills.cjs add <skill-name> [--local|--global]
 *   node .agents/scripts/manage-skills.cjs remove <skill-name>
 *   node .agents/scripts/manage-skills.cjs bootstrap [hub-path]
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const args = process.argv.slice(2);
const command = args[0] || 'list';

const rootDir = path.resolve(__dirname, '..', '..');
const agentsDir = path.join(rootDir, '.agents');
const skillsDir = path.join(agentsDir, 'skills');
const referencesDir = path.join(agentsDir, 'references');
const configFile = path.join(agentsDir, 'skills.json');

// 0. Commande d'amorce d'un nouveau projet vierge (Bootstrap)
if (command === 'bootstrap' || command === 'init') {
    const targetCwd = process.cwd();
    const explicitHub = args[1];
    const hubRoot = explicitHub ? path.resolve(explicitHub) : path.resolve(__dirname, '..');
    const hubSkills = fs.existsSync(path.join(hubRoot, 'skills'))
        ? path.join(hubRoot, 'skills')
        : path.join(hubRoot, '.agents', 'skills');

    console.log(`\n🚀 [BOOTSTRAP NOUVEAU PROJET] Initialisation dans : ${targetCwd}`);
    console.log(`📦 [SOURCE HUB GLOBAL]      : ${hubRoot}\n`);

    const targetAgents = path.join(targetCwd, '.agents');
    const targetSkills = path.join(targetAgents, 'skills');
    const targetReferences = path.join(targetAgents, 'references');
    const targetScripts = path.join(targetAgents, 'scripts');
    const targetRules = path.join(targetAgents, 'rules');
    const targetConfigFile = path.join(targetAgents, 'skills.json');

    [targetSkills, targetReferences, targetScripts, targetRules].forEach(dir => {
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    });

    // Lier le skill project-setup comme compétence d'amorce globale
    const targetSetupSkill = path.join(targetSkills, 'project-setup');
    const hubSetupSkill = path.join(hubSkills, 'project-setup');
    if (!fs.existsSync(targetSetupSkill) && fs.existsSync(hubSetupSkill)) {
        try {
            if (process.platform === 'win32') {
                execSync(`cmd /c mklink /J "${targetSetupSkill}" "${hubSetupSkill}"`, { stdio: 'pipe' });
            } else {
                fs.symlinkSync(hubSetupSkill, targetSetupSkill, 'dir');
            }
            console.log('🔗 Jonction NTFS créée : .agents/skills/project-setup -> Hub (Global)');
        } catch (e) {
            console.warn('⚠️ Avertissement jonction project-setup :', e.message);
        }
    }

    // Copier manage-skills.cjs
    const localScript = path.join(targetScripts, 'manage-skills.cjs');
    if (!fs.existsSync(localScript)) {
        fs.copyFileSync(__filename, localScript);
        console.log('📄 Script copié : .agents/scripts/manage-skills.cjs');
    }

    // Initialiser skills.json
    if (!fs.existsSync(targetConfigFile)) {
        const initialConfig = {
            hubPath: hubRoot,
            skills: {
                local: [],
                global: ['project-setup']
            }
        };
        fs.writeFileSync(targetConfigFile, JSON.stringify(initialConfig, null, 2) + '\n', 'utf8');
        console.log('📝 Manifeste créé : .agents/skills.json');
    }

    console.log(`\n🎉 Socle amorcé avec succès !\n`);
    console.log('👉 Étape suivante :');
    console.log('Demandez à votre Agent IA : « Initialise l agent pour ce projet »');
    process.exit(0);
}

// 1. Fonctions utilitaires de configuration
function loadConfig() {
    if (!fs.existsSync(configFile)) {
        console.error(`❌ Fichier de configuration introuvable : ${configFile}`);
        process.exit(1);
    }
    try {
        const raw = JSON.parse(fs.readFileSync(configFile, 'utf8'));
        // Normalisation : si c'est un tableau simple, convertir en { local: [...], global: [...] }
        if (Array.isArray(raw.skills)) {
            const normalized = { local: [], global: [] };
            raw.skills.forEach(skill => {
                const skillPath = path.join(skillsDir, skill);
                let isJunction = false;
                try {
                    isJunction = fs.lstatSync(skillPath).isSymbolicLink();
                } catch {}
                if (isJunction) {
                    normalized.global.push(skill);
                } else {
                    normalized.local.push(skill);
                }
            });
            raw.skills = normalized;
        }
        return raw;
    } catch (e) {
        console.error(`❌ Erreur de lecture dans ${configFile} :`, e.message);
        process.exit(1);
    }
}

function saveConfig(config) {
    fs.writeFileSync(configFile, JSON.stringify(config, null, 2) + '\n', 'utf8');
}

function getSkillStatus(skillName) {
    const target = path.join(skillsDir, skillName);
    if (!fs.existsSync(target)) return { exists: false, isGlobal: false };
    try {
        const lstat = fs.lstatSync(target);
        return { exists: true, isGlobal: lstat.isSymbolicLink() };
    } catch {
        return { exists: true, isGlobal: false };
    }
}

// 2. Analyse des dépendances de références dans un SKILL.md
function extractDependenciesFromSkill(skillDir) {
    const skillFile = path.join(skillDir, 'SKILL.md');
    if (!fs.existsSync(skillFile)) return [];
    const content = fs.readFileSync(skillFile, 'utf8');
    const matchedRefs = [];
    
    // Regex pour détecter les chemins du type .agents/references/... ou references/...
    const regex = /(?:\.agents[/\\])?references[/\\]([a-zA-Z0-9_\-/\\]+\.md)/g;
    let match;
    while ((match = regex.exec(content)) !== null) {
        const refRelativePath = match[1].replace(/\\/g, '/');
        if (!matchedRefs.includes(refRelativePath)) {
            matchedRefs.push(refRelativePath);
        }
    }
    return matchedRefs;
}

const config = loadConfig();
const hubRoot = config.hubPath;
const hubSkillsDir = fs.existsSync(path.join(hubRoot, 'skills'))
    ? path.join(hubRoot, 'skills')
    : path.join(hubRoot, '.agents', 'skills');
const hubReferencesDir = path.join(hubRoot, 'references');

switch (command) {
    case 'list': {
        console.log(`\n📋 [INVENTAIRE DES COMPÉTENCES DU PROJET]`);
        console.log(`📂 Répertoire : ${skillsDir}`);
        console.log(`📦 Hub Global : ${hubSkillsDir}\n`);

        const allConfigured = [
            ...(config.skills.local || []),
            ...(config.skills.global || [])
        ];

        console.log('--- 📁 Compétences Locales (Dossiers physiques dans le projet) :');
        (config.skills.local || []).forEach(skill => {
            const status = getSkillStatus(skill);
            const statusLabel = status.exists ? (status.isGlobal ? '⚠️ Configuré local mais lié en jonction' : '✅ Actif (Local)') : '❌ Manquant';
            console.log(`  - ${skill.padEnd(25)} [${statusLabel}]`);
        });

        console.log('\n--- 🔗 Compétences Globales (Liens symboliques vers le Hub) :');
        (config.skills.global || []).forEach(skill => {
            const status = getSkillStatus(skill);
            const statusLabel = status.exists ? (status.isGlobal ? '✅ Actif (Junction 🔗)' : '⚠️ Configuré global mais dossier physique') : '❌ Non lié';
            console.log(`  - ${skill.padEnd(25)} [${statusLabel}]`);
        });

        const fsItems = fs.existsSync(skillsDir)
            ? fs.readdirSync(skillsDir).filter(f => {
                try { return fs.statSync(path.join(skillsDir, f)).isDirectory(); } catch { return false; }
            })
            : [];

        const unmanaged = fsItems.filter(s => !allConfigured.includes(s));
        if (unmanaged.length > 0) {
            console.log('\n⚠️ Compétences présentes mais non déclarées dans skills.json :');
            unmanaged.forEach(skill => console.log(`  - ${skill}`));
        }

        console.log('');
        break;
    }

    case 'promote': {
        const skillName = args[1];
        if (!skillName) {
            console.error('❌ Usage : node manage-skills.cjs promote <nom-du-skill>');
            process.exit(1);
        }

        const localSkillPath = path.join(skillsDir, skillName);
        if (!fs.existsSync(localSkillPath)) {
            console.error(`❌ Le skill local n'existe pas dans : ${localSkillPath}`);
            process.exit(1);
        }

        const status = getSkillStatus(skillName);
        if (status.isGlobal) {
            console.log(`ℹ️ Le skill [${skillName}] est DÉJÀ global (lien symbolique vers le Hub).`);
            process.exit(0);
        }

        console.log(`\n🚀 [PROMOTION DÉPENDANCIELLE EN CASCADE] : ${skillName}`);
        console.log(`   Transfert du Local vers le Global (Hub)...\n`);

        // Étape 1 : Analyser et promouvoir les ressources partagées requises
        const dependencies = extractDependenciesFromSkill(localSkillPath);
        if (dependencies.length > 0) {
            console.log(`🔍 Dépendances de références détectées (${dependencies.length}) :`);
            dependencies.forEach(dep => {
                const localRef = path.join(referencesDir, dep);
                const hubRef = path.join(hubReferencesDir, dep);

                if (fs.existsSync(localRef)) {
                    const hubRefDir = path.dirname(hubRef);
                    if (!fs.existsSync(hubRefDir)) fs.mkdirSync(hubRefDir, { recursive: true });

                    // Si la ressource n'est pas encore dans le Hub, la copier
                    if (!fs.existsSync(hubRef)) {
                        fs.copyFileSync(localRef, hubRef);
                        console.log(`  🔗 [CASCADE] Référence partagée promue vers le Hub : references/${dep}`);
                    } else {
                        console.log(`  ℹ️ Référence partagée déjà présente dans le Hub : references/${dep}`);
                    }
                }
            });
        }

        // Étape 2 : Copier le skill vers le Hub
        const hubSkillPath = path.join(hubSkillsDir, skillName);
        if (!fs.existsSync(hubSkillsDir)) fs.mkdirSync(hubSkillsDir, { recursive: true });
        
        console.log(`📦 Déploiement physique dans le Hub : ${hubSkillPath}`);
        fs.cpSync(localSkillPath, hubSkillPath, { recursive: true });

        // Étape 3 : Supprimer le dossier local et le remplacer par une jonction NTFS
        fs.rmSync(localSkillPath, { recursive: true, force: true });
        try {
            if (process.platform === 'win32') {
                execSync(`cmd /c mklink /J "${localSkillPath}" "${hubSkillPath}"`, { stdio: 'pipe' });
            } else {
                fs.symlinkSync(hubSkillPath, localSkillPath, 'dir');
            }
            console.log(`🔗 Jonction NTFS créée : .agents/skills/${skillName} -> Hub`);
        } catch (e) {
            console.error(`❌ Échec de création de la jonction :`, e.message);
            process.exit(1);
        }

        // Étape 4 : Mettre à jour skills.json
        config.skills.local = (config.skills.local || []).filter(s => s !== skillName);
        if (!config.skills.global) config.skills.global = [];
        if (!config.skills.global.includes(skillName)) {
            config.skills.global.push(skillName);
        }
        saveConfig(config);

        console.log(`\n🎉 Félicitations ! Le skill [${skillName}] et ses dépendances sont désormais GLOBAUX.`);
        break;
    }

    case 'localize': {
        const skillName = args[1];
        if (!skillName) {
            console.error('❌ Usage : node manage-skills.cjs localize <nom-du-skill>');
            process.exit(1);
        }

        const targetSkillPath = path.join(skillsDir, skillName);
        const status = getSkillStatus(skillName);

        if (!status.exists) {
            console.error(`❌ Le skill [${skillName}] n'existe pas localement.`);
            process.exit(1);
        }

        if (!status.isGlobal) {
            console.log(`ℹ️ Le skill [${skillName}] est DÉJÀ local (dossier physique).`);
            process.exit(0);
        }

        console.log(`\n📁 [LOCALISATION / DÉTACHEMENT DU HUB] : ${skillName}`);
        const hubSkillPath = path.join(hubSkillsDir, skillName);
        
        // Supprimer la jonction NTFS (sans toucher au Hub)
        if (process.platform === 'win32') {
            execSync(`cmd /c rmdir "${targetSkillPath}"`, { stdio: 'pipe' });
        } else {
            fs.unlinkSync(targetSkillPath);
        }

        // Recréer le dossier physique en copiant depuis le Hub
        fs.cpSync(hubSkillPath, targetSkillPath, { recursive: true });
        console.log(`📋 Copie physique créée dans le projet : .agents/skills/${skillName}`);

        // Mettre à jour skills.json
        config.skills.global = (config.skills.global || []).filter(s => s !== skillName);
        if (!config.skills.local) config.skills.local = [];
        if (!config.skills.local.includes(skillName)) {
            config.skills.local.push(skillName);
        }
        saveConfig(config);

        console.log(`\n✅ Le skill [${skillName}] est maintenant LOCAL (indépendant du Hub pour incubation).\n`);
        break;
    }

    case 'install': {
        console.log(`\n🔄 Synchronisation des compétences déclarées...\n`);

        if (!fs.existsSync(skillsDir)) fs.mkdirSync(skillsDir, { recursive: true });

        // Synchroniser les compétences globales
        (config.skills.global || []).forEach(skill => {
            const dest = path.join(skillsDir, skill);
            const src = path.join(hubSkillsDir, skill);

            if (!fs.existsSync(dest)) {
                if (fs.existsSync(src)) {
                    try {
                        if (process.platform === 'win32') {
                            execSync(`cmd /c mklink /J "${dest}" "${src}"`, { stdio: 'pipe' });
                        } else {
                            fs.symlinkSync(src, dest, 'dir');
                        }
                        console.log(`🔗 Jonction créée (Global) : ${skill}`);
                    } catch (e) {
                        console.error(`❌ Échec liaison pour ${skill} :`, e.message);
                    }
                } else {
                    console.warn(`⚠️ Compétence globale introuvable dans le Hub : ${skill}`);
                }
            } else {
                console.log(`✅ Déjà monté (Global) : ${skill}`);
            }
        });

        // Vérifier la présence des compétences locales
        (config.skills.local || []).forEach(skill => {
            const dest = path.join(skillsDir, skill);
            if (fs.existsSync(dest)) {
                console.log(`📁 Présent (Local physique) : ${skill}`);
            } else {
                console.warn(`⚠️ Compétence locale déclarée mais absente physiquement : ${skill}`);
            }
        });

        console.log('\n✨ Synchronisation terminée avec succès.');
        break;
    }

    default:
        console.log(`Commande inconnue : ${command}`);
        console.log('Commandes disponibles : list, promote, localize, install, bootstrap');
        process.exit(1);
}
