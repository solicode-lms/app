#!/usr/bin/env node
/**
 * manage-skills.cjs — Gestionnaire Granulaire des Compétences & Dépendances IA
 *
 * Colocalisé dans le skill méta-gouvernance : project-setup/scripts/manage-skills.cjs
 *
 * Architecture :
 * - Local par défaut (Dossier physique dans .agents/skills/)
 * - Global par synchronisation physique (Solution B - Compatible Antigravity IDE)
 * - Promotion dépendancielle en cascade (promote)
 * - Incubation / détachement local (localize)
 * - Amorce de projet vierge (bootstrap avec copie physique)
 * - Synchronisation continue (sync)
 *
 * Usage :
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs list
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs pull [skill-name]     (Hub -> Projet local)
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs push [skill-name]     (Projet local -> Hub)
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs install
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs promote [skill-name]  (Alias de push)
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs localize <skill-name>
 *   node .agents/skills/project-setup/scripts/manage-skills.cjs bootstrap [hub-path]
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const args = process.argv.slice(2);
const command = args[0] || 'list';

// Détection ascendante dynamique du dossier racine du projet contenant .agents
function findProjectRoot(startDir) {
    let current = path.resolve(startDir);
    while (current !== path.dirname(current)) {
        if (fs.existsSync(path.join(current, '.agents'))) {
            return current;
        }
        current = path.dirname(current);
    }
    return process.cwd();
}

// Détection ascendante dynamique du Hub skills-workspace
function findHubRoot(startDir) {
    let current = path.resolve(startDir);
    while (current !== path.dirname(current)) {
        if (fs.existsSync(path.join(current, 'catalog.yaml')) && fs.existsSync(path.join(current, 'skills'))) {
            return current;
        }
        current = path.dirname(current);
    }
    return 'D:/solicode-lms/skills-workspace';
}

// 0. Commande d'amorce d'un nouveau projet vierge (Bootstrap)
if (command === 'bootstrap' || command === 'init') {
    const targetCwd = process.cwd();
    const explicitHub = args[1];
    const hubRoot = explicitHub ? path.resolve(explicitHub) : findHubRoot(__dirname);
    const hubSkills = path.join(hubRoot, 'skills');

    console.log(`\n🚀 [BOOTSTRAP NOUVEAU PROJET] Initialisation dans : ${targetCwd}`);
    console.log(`📦 [SOURCE HUB GLOBAL]      : ${hubRoot}\n`);

    const targetAgents = path.join(targetCwd, '.agents');
    const targetSkills = path.join(targetAgents, 'skills');
    const targetReferences = path.join(targetAgents, 'references');
    const targetRules = path.join(targetAgents, 'rules');
    const targetConfigFile = path.join(targetAgents, 'skills.json');

    [targetSkills, targetReferences, targetRules].forEach(dir => {
        if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    });

    // Déployer le skill project-setup comme compétence d'amorce physique (Solution B - Compatible Antigravity)
    const targetSetupSkill = path.join(targetSkills, 'project-setup');
    const hubSetupSkill = path.join(hubSkills, 'project-setup');
    if (fs.existsSync(hubSetupSkill)) {
        try {
            // Nettoyage si c'était une ancienne jonction
            if (fs.existsSync(targetSetupSkill)) {
                try {
                    if (fs.lstatSync(targetSetupSkill).isSymbolicLink()) {
                        if (process.platform === 'win32') execSync(`cmd /c rmdir "${targetSetupSkill}"`, { stdio: 'pipe' });
                        else fs.unlinkSync(targetSetupSkill);
                    }
                } catch {}
            }
            fs.cpSync(hubSetupSkill, targetSetupSkill, { recursive: true, force: true });
            console.log('📋 Copie physique créée : .agents/skills/project-setup (Solution B - Antigravity ready)');
        } catch (e) {
            console.warn('⚠️ Avertissement déploiement project-setup :', e.message);
        }
    }

    // Initialiser skills.json
    if (!fs.existsSync(targetConfigFile)) {
        const initialConfig = {
            hubPath: hubRoot.replace(/\\/g, '/'),
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

const rootDir = findProjectRoot(process.cwd());
const agentsDir = path.join(rootDir, '.agents');
const skillsDir = path.join(agentsDir, 'skills');
const referencesDir = path.join(agentsDir, 'references');
const configFile = path.join(agentsDir, 'skills.json');

// 1. Fonctions utilitaires de configuration
function loadConfig() {
    if (!fs.existsSync(configFile)) {
        console.error(`❌ Fichier de configuration introuvable : ${configFile}`);
        process.exit(1);
    }
    try {
        const raw = JSON.parse(fs.readFileSync(configFile, 'utf8'));
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

function extractDependenciesFromSkill(skillDir) {
    const skillFile = path.join(skillDir, 'SKILL.md');
    if (!fs.existsSync(skillFile)) return [];
    const content = fs.readFileSync(skillFile, 'utf8');
    const matchedRefs = [];
    
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
const hubRoot = config.hubPath || findHubRoot(__dirname);
const hubSkillsDir = path.join(hubRoot, 'skills');
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

        console.log('\n--- 🌐 Compétences Globales (Gérées via le Hub) :');
        (config.skills.global || []).forEach(skill => {
            const status = getSkillStatus(skill);
            const statusLabel = status.exists
                ? (status.isGlobal ? '🔗 Actif (Jonction NTFS)' : '✅ Actif (Physique - Compatible Antigravity)')
                : '❌ Non installé / Exécuter sync';
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

    case 'push':
    case 'promote': {
        const targetSkill = args[1];
        const skillsToPush = targetSkill
            ? [targetSkill]
            : (config.skills.global || []);

        if (skillsToPush.length === 0) {
            console.log(`ℹ️ Aucune compétence globale à pousser vers le Hub.`);
            break;
        }

        console.log(`\n🚀 [PUSH / PROMOTION VERS LE HUB GLOBAL] (Projet local ➔ Hub)`);
        console.log(`📂 Projet source : ${skillsDir}`);
        console.log(`📦 Hub cible     : ${hubSkillsDir}\n`);

        skillsToPush.forEach(skillName => {
            const localSkillPath = path.join(skillsDir, skillName);
            if (!fs.existsSync(localSkillPath)) {
                console.warn(`⚠️ Compétence locale introuvable : ${localSkillPath}`);
                return;
            }

            console.log(`📤 Poussée de [${skillName}] vers le Hub...`);

            // Étape 1 : Analyser et promouvoir les ressources partagées requises
            const dependencies = extractDependenciesFromSkill(localSkillPath);
            if (dependencies.length > 0) {
                dependencies.forEach(dep => {
                    const localRef = path.join(referencesDir, dep);
                    const hubRef = path.join(hubReferencesDir, dep);

                    if (fs.existsSync(localRef)) {
                        const hubRefDir = path.dirname(hubRef);
                        if (!fs.existsSync(hubRefDir)) fs.mkdirSync(hubRefDir, { recursive: true });

                        fs.copyFileSync(localRef, hubRef);
                        console.log(`  └─ 🔗 Référence partagée mise à jour dans le Hub : references/${dep}`);
                    }
                });
            }

            // Étape 2 : Copier le skill vers le Hub
            const hubSkillPath = path.join(hubSkillsDir, skillName);
            if (!fs.existsSync(hubSkillsDir)) fs.mkdirSync(hubSkillsDir, { recursive: true });
            
            fs.cpSync(localSkillPath, hubSkillPath, { recursive: true, force: true });
            console.log(`  └─ 📦 Déploiement physique dans le Hub : ${hubSkillPath}`);

            // Étape 3 : Conserver le dossier local comme copie physique (Solution B - Compatible Antigravity)
            console.log(`  └─ 📋 Copie physique maintenue dans le projet local : .agents/skills/${skillName}`);

            // Étape 4 : Mettre à jour skills.json si c'était un skill local
            if ((config.skills.local || []).includes(skillName)) {
                config.skills.local = config.skills.local.filter(s => s !== skillName);
                if (!config.skills.global) config.skills.global = [];
                if (!config.skills.global.includes(skillName)) {
                    config.skills.global.push(skillName);
                }
            }
        });

        saveConfig(config);
        console.log(`\n🎉 Poussée vers le Hub terminée avec succès !`);
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
        
        if (process.platform === 'win32') {
            execSync(`cmd /c rmdir "${targetSkillPath}"`, { stdio: 'pipe' });
        } else {
            fs.unlinkSync(targetSkillPath);
        }

        fs.cpSync(hubSkillPath, targetSkillPath, { recursive: true });
        console.log(`📋 Copie physique créée dans le projet : .agents/skills/${skillName}`);

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
        console.log(`\n📦 [INSTALLATION DES COMPÉTENCES (SOLUTION B - PHYSIQUE)]`);
        console.log(`📦 Hub source   : ${hubSkillsDir}`);
        console.log(`📂 Projet local : ${skillsDir}\n`);

        if (!fs.existsSync(skillsDir)) fs.mkdirSync(skillsDir, { recursive: true });

        (config.skills.global || []).forEach(skill => {
            const dest = path.join(skillsDir, skill);
            const src = path.join(hubSkillsDir, skill);

            if (!fs.existsSync(src)) {
                console.warn(`⚠️ Compétence globale introuvable dans le Hub : ${skill}`);
                return;
            }

            let isJunction = false;
            if (fs.existsSync(dest)) {
                try {
                    isJunction = fs.lstatSync(dest).isSymbolicLink();
                } catch {}
            }

            // Solution B : Si absent ou si c'est une jonction NTFS (incompatible Antigravity), déployer en copie physique
            if (!fs.existsSync(dest) || isJunction) {
                if (isJunction) {
                    try {
                        if (process.platform === 'win32') execSync(`cmd /c rmdir "${dest}"`, { stdio: 'pipe' });
                        else fs.unlinkSync(dest);
                        console.log(`🧹 Ancienne jonction convertie en dossier physique : ${skill}`);
                    } catch (e) {}
                }
                fs.cpSync(src, dest, { recursive: true, force: true });
                console.log(`✅ Déployé (Copie physique Solution B) : ${skill}`);
            } else {
                console.log(`✅ Déjà présent (Dossier physique) : ${skill}`);
            }

            // Installer également les références associées
            const dependencies = extractDependenciesFromSkill(src);
            if (dependencies.length > 0) {
                dependencies.forEach(dep => {
                    const hubRef = path.join(hubReferencesDir, dep);
                    const localRef = path.join(referencesDir, dep);
                    if (fs.existsSync(hubRef)) {
                        const localRefDir = path.dirname(localRef);
                        if (!fs.existsSync(localRefDir)) fs.mkdirSync(localRefDir, { recursive: true });
                        if (!fs.existsSync(localRef)) {
                            fs.copyFileSync(hubRef, localRef);
                            console.log(`   └─ 🔗 Référence installée : references/${dep}`);
                        }
                    }
                });
            }
        });

        (config.skills.local || []).forEach(skill => {
            const dest = path.join(skillsDir, skill);
            if (fs.existsSync(dest)) {
                console.log(`📁 Présent (Local physique) : ${skill}`);
            } else {
                console.warn(`⚠️ Compétence locale déclarée mais absente physiquement : ${skill}`);
            }
        });

        console.log('\n✨ Installation terminée avec succès (Toutes les compétences sont prêtes pour Antigravity).');
        break;
    }

    case 'sync':
    case 'update':
    case 'pull': {
        const targetSkill = args[1];
        console.log(`\n🔄 [SYNCHRONISATION DES COMPÉTENCES DEPUIS LE HUB]`);
        console.log(`📦 Hub source   : ${hubSkillsDir}`);
        console.log(`📂 Projet local : ${skillsDir}\n`);

        const skillsToSync = targetSkill
            ? [targetSkill]
            : (config.skills.global || []);

        if (skillsToSync.length === 0) {
            console.log(`ℹ️ Aucune compétence globale à synchroniser.`);
            break;
        }

        skillsToSync.forEach(skill => {
            const src = path.join(hubSkillsDir, skill);
            const dest = path.join(skillsDir, skill);

            if (!fs.existsSync(src)) {
                console.warn(`⚠️ Compétence [${skill}] introuvable dans le Hub : ${src}`);
                return;
            }

            // Si c'est une jonction/symlink existant, le supprimer proprement pour poser un dossier physique
            if (fs.existsSync(dest)) {
                try {
                    const stat = fs.lstatSync(dest);
                    if (stat.isSymbolicLink()) {
                        if (process.platform === 'win32') {
                            execSync(`cmd /c rmdir "${dest}"`, { stdio: 'pipe' });
                        } else {
                            fs.unlinkSync(dest);
                        }
                    }
                } catch (e) {}
            }

            // Copie physique intégrale (compatible Antigravity IDE)
            fs.cpSync(src, dest, { recursive: true, force: true });
            console.log(`✅ Synchronisé (Copie physique) : ${skill}`);

            // Synchroniser également les dépendances de références du Hub
            const dependencies = extractDependenciesFromSkill(src);
            if (dependencies.length > 0) {
                dependencies.forEach(dep => {
                    const hubRef = path.join(hubReferencesDir, dep);
                    const localRef = path.join(referencesDir, dep);
                    if (fs.existsSync(hubRef)) {
                        const localRefDir = path.dirname(localRef);
                        if (!fs.existsSync(localRefDir)) fs.mkdirSync(localRefDir, { recursive: true });
                        fs.copyFileSync(hubRef, localRef);
                        console.log(`   └─ 🔗 Référence mise à jour : references/${dep}`);
                    }
                });
            }
        });

        console.log(`\n✨ Synchronisation terminée avec succès ! Compétences reconnues par Antigravity.\n`);
        break;
    }

    default:
        console.log(`Commande inconnue : ${command}`);
        console.log('Commandes disponibles : list, pull (sync), push (promote), localize, install, bootstrap');
        process.exit(1);
}
