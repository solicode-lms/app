# 🤖 Assistant IA & Skills Solicode LMS

Bienvenue dans l'espace de configuration et de pilotage de l'Agent IA Solicode LMS.
Cet environnement normalisé dote votre assistant de **9 compétences modulaires**, de **directives d'intégrité strictes** et de **fiches de référence techniques** connectées au Hub Global.

---

## 🧭 Guides & Documentation Développeur
Chaque compétence dispose de son propre guide d'utilisation dédié (`README.md`) expliquant son rôle, ses scénarios types, ses prompts recommandés et ses garde-fous :
- [`project-setup`](skills/project-setup/README.md) : Guide d'initialisation, bootstrapping et profils
- [`plugin-dev`](skills/plugin-dev/README.md) : Guide de gouvernance et validation des skills
- [`feature-dev`](skills/feature-dev/README.md) : Guide de développement 3-tiers et Blade/TableUI
- [`domain-scaffolder`](skills/domain-scaffolder/README.md) : Guide de modélisation et migrations
- [`bug-fixer`](skills/bug-fixer/README.md) : Guide de diagnostic sur logs et fiches d'incidents
- [`context-generator`](skills/context-generator/README.md) : Guide de cartographie contextuelle des packages
- [`planner-dev`](skills/planner-dev/README.md) : Guide d'analyse d'impact et cadrage /plan

---

## ⚡ Prompts d'Invocation Rapide

| N° | Votre Intention | Prompt Recommandé | Guide Humain | Instructions IA |
| :--- | :--- | :--- | :--- | :--- |
| **1** | **Audit & Setup initial** | *« Initialise l'agent pour ce projet »* | [`README.md`](skills/project-setup/README.md) | [`SKILL.md`](skills/project-setup/SKILL.md) |
| **2** | **Contexte d'un package** | *« Génère le contexte IA pour modules/PkgFormation »* | [`README.md`](skills/context-generator/README.md) | [`SKILL.md`](skills/context-generator/SKILL.md) |
| **3** | **Étude d'impact préalable** | *« Analyse l'impact de l'ajout d'une table d'émargement /plan »* | [`README.md`](skills/planner-dev/README.md) | [`SKILL.md`](skills/planner-dev/SKILL.md) |
| **4** | **Nouvelle entité BDD** | *« Crée l'entité SessionFormation dans PkgFormation »* | [`README.md`](skills/domain-scaffolder/README.md) | [`SKILL.md`](skills/domain-scaffolder/SKILL.md) |
| **5** | **Développement Service** | *« Implémente le hook afterCreateRules pour notifier les formateurs »* | [`README.md`](skills/feature-dev/README.md) | [`SKILL.md`](skills/feature-dev/SKILL.md) |
| **6** | **Personnalisation UI** | *« Crée une vue custom pour afficher un badge de statut dans TableUI »* | [`README.md`](skills/feature-dev/README.md) | [`SKILL.md`](skills/feature-dev/SKILL.md) |
| **7** | **Correction de bug log** | *« Analyse la dernière erreur dans laravel.log et applique le fix »* | [`README.md`](skills/bug-fixer/README.md) | [`SKILL.md`](skills/bug-fixer/SKILL.md) |
| **8** | **Évolution d'une règle** | *« Ajoute une règle interdisant les requêtes directes allQuery »* | [`README.md`](skills/plugin-dev/README.md) | [`SKILL.md`](skills/plugin-dev/SKILL.md) |

*(Note : les compétences pédagogiques `tutorial-writer` et `workshop-builder` du Hub sont exclues de ce projet via `.agents/skills.json`).*

---

## 📁 Architecture des Dossiers `.agents/`

Toutes les ressources de l'agent sont intégrées sous `.agents/` avec installation sélective :

```
.agents/
├── skills.json   ➔ Manifeste déclaratif des compétences (locales vs liens Hub)
├── skills/       ➔ 7 compétences (avec leurs README, scripts et templates dédiés)
├── references/   ➔ Fiches de référence partagées (Gapp, TableUI, Widgets, 3-tiers)
├── rules/        ➔ Règles Antigravity (critical-rules.md, affichage-skills.md, gestion-issues.md, etc.)
├── scripts/      ➔ deploy-update.cjs (script applicatif d'importation CSV)
└── tests/        ➔ Traçabilité et retours d'expérience (feedbacks/INC-xxx.yaml)
```

---

## 🛡️ Règles Inviolables pour le Développeur & l'Agent

1. **Classes `Base/` intouchables :** Ne jamais modifier les fichiers dans les sous-dossiers `Base/`. Toujours surcharger dans la classe enfant PHP.
2. **Vues Blade verrouillées :** Ne jamais modifier une vue avec l'en-tête `ESSARRAJ Fouad`. Utiliser systématiquement le dossier `custom/`.
3. **Migrations contrôlées :** L'agent génère les migrations avec clé unique `reference`, mais **n'exécute jamais** `php artisan migrate` en aveugle.
4. **Mode Consultation :** Terminez votre invite par **`?`** pour forcer l'agent à répondre en lecture seule sans modifier aucun fichier.
