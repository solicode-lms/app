# Guide d'Utilisation : Skill `project-setup` (v2.0.0)

> **Compétence :** `project-setup`  
> **Portée :** Globale (Partagée par le Hub `skills-workspace`)  
> **Catégorie :** Onboarding Multi-Projets, Bootstrapping, Recyclage Legacy & Profilage  
> **Fichier technique IA :** [`SKILL.md`](SKILL.md)

---

## 🎯 1. À quoi sert ce Skill ?

Le skill `project-setup` est l'**orchestrateur universel d'initialisation et de diagnostic d'agent IA**. Il permet :
1. D'amorcer n'importe quel type de projet (Application Dev Laravel 3-tiers, Dépôt de cours et tutoriels Markdown, ou Référentiel d'ateliers et évaluations APC).
2. D'analyser le cahier des charges et de qualifier le profil adapté (avec dialogue interactif si le projet est vierge ou sans documentation).
3. D'auditer et recycler un ancien dossier historique (`old_agent`, `.agent/`) pour en extraire les règles métiers et archiver le superflu dans `archive/old_agent/`.
4. De configurer le déploiement et la synchronisation granulaire des compétences nécessaires via des copies physiques déterministes (Solution B - Compatible Antigravity IDE).

---

## 🚀 2. Amorcer un Nouveau Projet Vierge (Sans `.agents` ni aucun skill)

Si vous créez un tout nouveau projet (dossier vide), amorcez le socle en une seule commande terminal depuis la racine de votre nouveau projet :

```powershell
node D:\solicode-lms\skills-workspace\skills\project-setup\scripts\manage-skills.cjs bootstrap
```

**Actions automatiques exécutées :**
1. **Création du socle :** Arborescence `.agents/skills/`.
2. **Déploiement physique du skill d'amorce (Solution B) :** Copie physique de `project-setup` (`D:\solicode-lms\skills-workspace\skills\project-setup`) pour une reconnaissance immédiate par Antigravity IDE.
3. **Manifeste initial :** Création de `.agents/skills.json` initialisé avec `project-setup`.

Ouvrez ensuite votre projet dans l'IDE et lancez simplement le prompt ci-dessous.

---

## 🛠️ 3. Outil de Gestion Déterministe (`scripts/manage-skills.cjs`)

Le script de gouvernance et de synchronisation est colocalisé dans ce skill :

```powershell
# 1. Consulter l'inventaire des compétences (locales vs globales Hub)
node .agents/skills/project-setup/scripts/manage-skills.cjs list

# 2. Récupérer / Synchroniser toutes les compétences globales depuis le Hub (Hub -> Projet)
node .agents/skills/project-setup/scripts/manage-skills.cjs pull
# (ou avec un nom de skill : node ... pull plugin-dev)

# 3. Pousser / Synchroniser toutes les compétences globales vers le Hub (Projet -> Hub)
node .agents/skills/project-setup/scripts/manage-skills.cjs push
# (ou avec un nom de skill : node ... push plugin-dev)

# 4. Détacher une compétence globale pour modification locale (incubation)
node .agents/skills/project-setup/scripts/manage-skills.cjs localize <nom-du-skill>
```

---

## 🔄 4. Comment Synchroniser les Compétences (Hub ↔ Projet Local)

### ⚠️ Pourquoi une synchronisation physique sous Antigravity ?
Par défaut, **Antigravity IDE n'explore pas les liens symboliques ni les jonctions NTFS** (`mklink /J`) lors de la découverte automatique des compétences dans `.agents/skills/` (sécurité et prévention des boucles récursives). 

Par conséquent, les compétences doivent résider sous forme de **répertoires physiques** dans le projet pour être chargées par l'agent. La synchronisation permet de maintenir ces copies physiques parfaitement alignées avec le Hub sans manipulation manuelle risquée.

### A. Synchronisation Descendante (Hub Global ➔ Projet Local)
Pour mettre à jour les compétences locales lorsque le Hub `skills-workspace` a évolué :

1. **Méthode CLI automatique (Recommandée) :**
   ```powershell
   node .agents/skills/project-setup/scripts/manage-skills.cjs sync
   ```
   *Ce script :*
   - Lit la liste `skills.global` dans `.agents/skills.json`.
   - Remplace proprement toute ancienne jonction NTFS par un répertoire physique.
   - Copie les fichiers à jour depuis `D:\solicode-lms\skills-workspace\skills\`.
   - Met à jour automatiquement les dépendances de référence associées sous `.agents/references/`.

2. **Alternative PowerShell native (sans Node.js) :**
   ```powershell
   Copy-Item -Path "D:\solicode-lms\skills-workspace\skills\plugin-dev" -Destination ".agents\skills\plugin-dev" -Recurse -Force
   Copy-Item -Path "D:\solicode-lms\skills-workspace\skills\project-setup" -Destination ".agents\skills\project-setup" -Recurse -Force
   ```

### B. Synchronisation Montante (Projet Local ➔ Hub Global)
Pour envoyer vos modifications locales vers le Hub central :

```powershell
# Pousser TOUTES les compétences globales vers le Hub (sans argument)
node .agents/skills/project-setup/scripts/manage-skills.cjs push

# Ou pousser / promouvoir une compétence spécifique
node .agents/skills/project-setup/scripts/manage-skills.cjs push <nom-du-skill>
# (alias promote : node ... promote <nom-du-skill>)
```
*Le script déploie les dossiers vers le Hub et transfère automatiquement les fiches de référence sous `.agents/references/` déclarées dans les `SKILL.md`.*

---

## 💬 5. Comment l'Invoquer dans l'IDE ? (Prompts Clés en Main)

### A. Initialisation Standard (Application Dev / Laravel)
- *« Initialise l'agent pour ce projet. »*
- *« Configure l'environnement IA pour notre application. »*

### B. Projets Pédagogiques ou Editoriaux
- *« Configure l'agent pour un projet de rédaction de tutoriels d'autoformation web. »*
- *« Initialise le setup pour concevoir des ateliers pratiques et grilles d'évaluation APC. »*

### C. Recyclage d'un Ancien Agent Historique
- *« Audite et migre notre ancien dossier old_agent pour assainir l'environnement. »*

---

## 📚 6. Ressources Méthodologiques Associées (Colocalisation)

Ce skill intègre directement ses propres guides d'ingénierie et ses scripts dans son sous-dossier :
- [`scripts/manage-skills.cjs`](scripts/manage-skills.cjs) : Gestionnaire CLI autonome multi-projets.
- [`resources/project-typology-and-profiles.md`](resources/project-typology-and-profiles.md) : Matrice des 4 profils de projets et compétences associées.
- [`resources/legacy-migration-playbook.md`](resources/legacy-migration-playbook.md) : Procédure de tri passif/actif et protocole d'archivage d'`old_agent`.
- [`resources/onboarding-workflow.md`](resources/onboarding-workflow.md) : Déroulé pas-à-pas du bootstrapping et du dialogue interactif.
