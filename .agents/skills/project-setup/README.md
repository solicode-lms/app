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
4. De configurer l'installation granulaire des compétences nécessaires via des jonctions NTFS unitaires.

---

## 🚀 2. Amorcer un Nouveau Projet Vierge (Sans `.agents` ni aucun skill)

Si vous créez un tout nouveau projet (dossier vide), amorcez le socle en une seule commande terminal depuis la racine de votre nouveau projet :

```powershell
node D:\solicode-lms\skills-workspace\skills\project-setup\scripts\manage-skills.cjs bootstrap
```

**Actions automatiques exécutées :**
1. **Création du socle :** Arborescence `.agents/skills/`.
2. **Liaison NTFS du skill d'amorce :** Jonction unitaire vers `project-setup` (`D:\solicode-lms\skills-workspace\skills\project-setup`).
3. **Manifeste initial :** Création de `.agents/skills.json` initialisé avec `project-setup`.

Ouvrez ensuite votre projet dans l'IDE et lancez simplement le prompt ci-dessous.

---

## 🛠️ 3. Outil de Gestion Déterministe (`scripts/manage-skills.cjs`)

Le script de gouvernance et d'installation sélective est colocalisé dans ce skill :

```powershell
# 1. Consulter l'inventaire des compétences (locales vs liens symboliques Hub)
node .agents/skills/project-setup/scripts/manage-skills.cjs list

# 2. Installer sélectivement une compétence partagée depuis le Hub
node .agents/skills/project-setup/scripts/manage-skills.cjs install plugin-dev

# 3. Retirer une compétence partagée du projet
node .agents/skills/project-setup/scripts/manage-skills.cjs uninstall plugin-dev

# 4. Promouvoir une compétence locale vers le Hub (avec promotion des références en cascade)
node .agents/skills/project-setup/scripts/manage-skills.cjs promote <nom-du-skill>
```

---

## 💬 4. Comment l'Invoquer dans l'IDE ? (Prompts Clés en Main)

### A. Initialisation Standard (Application Dev / Laravel)
- *« Initialise l'agent pour ce projet. »*
- *« Configure l'environnement IA pour notre application. »*

### B. Projets Pédagogiques ou Editoriaux
- *« Configure l'agent pour un projet de rédaction de tutoriels d'autoformation web. »*
- *« Initialise le setup pour concevoir des ateliers pratiques et grilles d'évaluation APC. »*

### C. Recyclage d'un Ancien Agent Historique
- *« Audite et migre notre ancien dossier old_agent pour assainir l'environnement. »*

---

## 📚 5. Ressources Méthodologiques Associées (Colocalisation)

Ce skill intègre directement ses propres guides d'ingénierie et ses scripts dans son sous-dossier :
- [`scripts/manage-skills.cjs`](scripts/manage-skills.cjs) : Gestionnaire CLI autonome multi-projets.
- [`resources/project-typology-and-profiles.md`](resources/project-typology-and-profiles.md) : Matrice des 4 profils de projets et compétences associées.
- [`resources/legacy-migration-playbook.md`](resources/legacy-migration-playbook.md) : Procédure de tri passif/actif et protocole d'archivage d'`old_agent`.
- [`resources/onboarding-workflow.md`](resources/onboarding-workflow.md) : Déroulé pas-à-pas du bootstrapping et du dialogue interactif.
