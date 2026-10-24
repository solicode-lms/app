# Typologies de Projets & Profils de Compétences IA

> **Document de référence — Matrice de Qualification & Sélection Granulaire des Skills**  
> **Composant :** `project-setup` v2.0.0 (Progressive Disclosure Resource)

---

## 1. Objectif de la Matrice

Tous les projets n'ont pas la même finalité. Un projet de **rédaction de tutoriels d'autoformation** ne doit pas être encombré de compétences de scaffolding de base de données (`domain-scaffolder`) ou de diagnostic de logs SQL (`bug-fixer`). Inversement, un projet applicatif **Laravel 3-tiers** a besoin de respecter les verrous Gapp et TableUI sans charger d'outils de pédagogie APC.

Ce document définit la matrice d'adéquation entre la typologie détectée d'un projet et le sous-ensemble strict de compétences du Hub Global à activer dans `.agents/skills.json`.

---

## 2. Les 4 Profils Typologiques Canoniques

### Profil 1 : Application de Développement Logiciel (Dev Full-Stack / 3-Tiers)
*Développement, maintenance et extension d'une application informatique avec base de données et interface.*

- **Indicateurs de détection automatique :**
  - Fichiers de gestion de dépendances de code : `composer.json`, `package.json`, `pom.xml`, `requirements.txt`, `go.mod`.
  - Arborescences modulaires ou applicatives : `modules/`, `app/`, `src/`, `database/migrations/`, `routes/`.
  - Schémas relationnels et ORM : Modèles Eloquent, migrations, contrôleurs HTTP.
  - Spécificités Solicode LMS : présence de `PkgGapp`, composants `TableUI.js`, `FilterUI.js`, verrous `ESSARRAJ Fouad`.
- **Compétences IA recommandées :**
  - `project-setup` : Orchestration et diagnostic local.
  - `planner-dev` : Analyse d'impact architectural et plan d'action multi-couches sans modification directe de code.
  - `feature-dev` : Développement traversant (Service, Trait, Controller, Blade, TableUI).
  - `domain-scaffolder` : Création et modélisation d'entités métier multi-tiers et migrations.
  - `bug-fixer` : Diagnostic sur logs (`laravel.log`), exceptions `BlException` et fiches d'incidents.
  - `context-generator` : Cartographie contextuelle `AI_CONTEXT.md` des packages et modules.
  - `plugin-dev` : Raffinement des règles, guidelines et gouvernance des skills.
- **Compétences exclues par défaut :**
  - `tutorial-writer`, `workshop-builder`.

---

### Profil 2 : Rédaction de Tutoriels & Autoformations (Éditorial & Didactique)
*Projets axés sur la production de supports de formation, guides pas-à-pas, documentation technique pédagogique ou modules d'autoformation (ex: autoformations-web).*

- **Indicateurs de détection automatique :**
  - Dépôts centrés sur Markdown / MDX : dossiers `tutoriels/`, `docs/`, `guides/`, `cours/`, `autoformations/`.
  - Moteurs de documentation : `docusaurus.config.js`, `.vitepress/`, `mkdocs.yml`, `book.json`.
  - Absence de backend applicatif lourd (ou présence de sous-dossiers `demos/` ou `sandbox/` purement illustratifs).
  - Présence d'un cahier des charges éditorial (objectifs pédagogiques, compétences ciblées, persona apprenant).
- **Compétences IA recommandées :**
  - `project-setup` : Diagnostic et maintien du cadre éditorial.
  - `planner-dev` : Structuration du plan de cours, décomposition en étapes progressives (macro-plan).
  - `tutorial-writer` : Rédaction détaillée des étapes, insertion de snippets vérifiables, quiz et auto-évaluations.
  - `plugin-dev` : Création ou enrichissement de micro-guides spécifiques au domaine enseigné.
- **Compétences exclues par défaut :**
  - `domain-scaffolder`, `bug-fixer`, `context-generator`, `feature-dev` (sauf si le tutoriel inclut une application témoin à maintenir).

---

### Profil 3 : Ingénierie Pédagogique & Ateliers APC (Évaluation & Compétences)
*Conception de référentiels de compétences, situations professionnelles d'évaluation, grilles critériées selon l'Approche Par Compétences (APC) et briefs de projets.*

- **Indicateurs de détection automatique :**
  - Dossiers de type `ateliers/`, `briefs/`, `evaluations/`, `competences/`, `situations-pro/`.
  - Références explicites aux référentiels métiers (REAC, grilles critériées APC, critères minimaux vs critères de perfectionnement).
- **Compétences IA recommandées :**
  - `project-setup` : Cadrage du projet pédagogique.
  - `planner-dev` : Scénarisation de situations d'évaluation et découpage des jalons d'un projet.
  - `workshop-builder` : Conception d'ateliers pratiques guidés (TP), briefs de projet et grilles d'évaluation APC.
  - `tutorial-writer` : Rédaction des supports méthodologiques associés.
  - `plugin-dev` : Normalisation des fiches d'évaluation.
- **Compétences exclues par défaut :**
  - `domain-scaffolder`, `bug-fixer`, `context-generator`, `feature-dev`.

---

### Profil 4 : Projet Hybride / Sur Mesure
*Projet combinant à la fois un socle applicatif réel et une suite de tutoriels ou d'ateliers intégrés.*

- L'attribution se fait à la carte dans `.agents/skills.json` selon l'analyse du cahier des charges.

---

## 3. Protocole de Qualification Interactive (Projet Vierge ou Ambigü)

Lorsque le projet ne contient aucun fichier caractéristique (projet vierge, dossier vide, ou absence de cahier des charges explicite), `project-setup` **ne prend aucune décision arbitraire**. Il engage un dialogue interactif avec le développeur :

```text
❓ [QUALIFICATION DU PROJET] 
Aucun cahier des charges ou structure caractéristique n'a été détecté.
Pour configurer l'environnement IA optimal, veuillez préciser la finalité principale de ce projet :

1. [Dev Logiciel] Développer une application complète (Laravel, Node.js, Frontend, Base de données).
2. [Tutoriels / Autoformation] Rédiger des tutoriels, cours Markdown, guides pas-à-pas ou documentation.
3. [Ingénierie APC] Concevoir des briefs de projets, ateliers de travaux pratiques ou grilles d'évaluation.
4. [Hybride] Projet mixte combinant développement et documentation pédagogique.
```

Dès la réponse reçue, `project-setup` génère la configuration `.agents/skills.json` correspondante et lance l'installation.
