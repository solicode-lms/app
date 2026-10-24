# 🤖 Assistant IA & Skills Solicode LMS

Bienvenue dans l'espace de configuration et de pilotage de l'Agent IA Solicode LMS.
Cet environnement normalisé dote votre assistant de **9 compétences modulaires**, de **directives d'intégrité strictes** et de **fiches de référence techniques** connectées au Hub Global.

---

## 🧭 Guide Complet & Documentation
Pour le guide pas-à-pas complet, consultez :
👉 [**Guide Officiel d'Utilisation des Skills**](references/usage/guide-utilisation-skills.md)

---

## ⚡ Top 10 des Prompts d'Invocation Rapide

| N° | Votre Intention | Prompt Recommandé | Skill Mobilisé |
| :--- | :--- | :--- | :--- |
| **1** | **Audit & Setup initial** | *« Initialise l'agent pour ce projet »* | [`project-setup`](skills/project-setup/SKILL.md) |
| **2** | **Contexte d'un package** | *« Génère le contexte IA pour modules/PkgFormation »* | [`context-generator`](skills/context-generator/SKILL.md) |
| **3** | **Étude d'impact préalable** | *« Analyse l'impact de l'ajout d'une table d'émargement /plan »* | [`planner-dev`](skills/planner-dev/SKILL.md) |
| **4** | **Nouvelle entité BDD** | *« Crée l'entité SessionFormation dans PkgFormation »* | [`domain-scaffolder`](skills/domain-scaffolder/SKILL.md) |
| **5** | **Développement Service** | *« Implémente le hook afterCreateRules pour notifier les formateurs »* | [`feature-dev`](skills/feature-dev/SKILL.md) |
| **6** | **Personnalisation UI** | *« Crée une vue custom pour afficher un badge de statut dans TableUI »* | [`feature-dev`](skills/feature-dev/SKILL.md) |
| **7** | **Correction de bug log** | *« Analyse la dernière erreur dans laravel.log et applique le fix »* | [`bug-fixer`](skills/bug-fixer/SKILL.md) |
| **8** | **Évolution d'une règle** | *« Ajoute une règle interdisant les requêtes directes allQuery »* | [`plugin-dev`](skills/plugin-dev/SKILL.md) |

*(Note : les compétences pédagogiques `tutorial-writer` et `workshop-builder` du Hub sont exclues de ce projet via `.agents/skills.json`).*

---

## 📁 Architecture des Dossiers `.agents/`

Toutes les ressources de l'agent sont intégrées sous `.agents/` avec installation sélective :

```
.agents/
├── skills.json   ➔ Manifeste déclaratif des compétences actives
├── skills/       ➔ 7 compétences installées (feature-dev, bug-fixer, planner-dev...)
├── references/   ➔ Fiches de référence (3-tiers, Gapp, TableUI, Widgets, Usage)
├── standards/    ➔ critical-rules.md (règles absolues), principes généraux
├── templates/    ➔ Gabarits normés de compétences et contextes
├── scripts/      ➔ manage-skills.cjs, validate-skills.cjs, check-references.cjs
├── tests/        ➔ Évaluations et fiches d'incidents (feedbacks/INC-xxx.yaml)
├── local/        ➔ Conventions métier locales Solicode LMS
└── rules/        ➔ Directives comportementales (affichage-skills.md)
```

---

## 🛡️ Règles Inviolables pour le Développeur & l'Agent

1. **Classes `Base/` intouchables :** Ne jamais modifier les fichiers dans les sous-dossiers `Base/`. Toujours surcharger dans la classe enfant PHP.
2. **Vues Blade verrouillées :** Ne jamais modifier une vue avec l'en-tête `ESSARRAJ Fouad`. Utiliser systématiquement le dossier `custom/`.
3. **Migrations contrôlées :** L'agent génère les migrations avec clé unique `reference`, mais **n'exécute jamais** `php artisan migrate` en aveugle.
4. **Mode Consultation :** Terminez votre invite par **`?`** pour forcer l'agent à répondre en lecture seule sans modifier aucun fichier.
