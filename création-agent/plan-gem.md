Voici le **Plan Directeur Global, Intégré et Définitif** de votre architecture de Skills IA. Il synthétise l'ensemble de notre parcours : l'analyse des flux de travail, l'architecture hybride unifiée (Hub global + Contexte local par projet), les standards ouverts de l'écosystème **Agent Skills** (`agentskills.io`), et les bonnes pratiques inspirées des dépôts officiels de référence comme **Anthropic**.

---

# PLAN DIRECTEUR GLOBAL : CONCEPTION ET GESTION DES SKILLS IA

## PARTIE 1 : ANALYSE PRÉALABLE DES FLUX DE TRAVAIL (Le Point de Départ)

*Objectif : Auditer le terrain pour recenser les tâches récurrentes avant d'écrire le moindre Skill, en séparant les technologies (références) des processus (skills).*

1. **Recensement Brut des Activités :**
* *Conception :* Création de packages Active LMS, modélisation de bases de données (tables SQL/MySQL), définition des relations de classes (`Category` vs `Item`), rédaction des `AI_CONTEXT.md`.
* *Développement :* Écriture de migrations, structuration de la couche Service/Data, création de CRUD, **ajout de filtres**, intégration des composants internes (`TableUI` / `FormUI`).
* *Pédagogie :* Rédaction de tutoriels pas-à-pas, création de TP, d'exercices d'application ou de grilles d'évaluation par compétences.


2. **Test de Récurrence :** Isoler les tâches répétées au moins 3 fois par mois.
3. **Découpage Séquentiel :** Formuler pour chaque flux le *Déclencheur*, les *Entrées*, la *Séquence d'actions* et le *Livrable*.
4. **Analyse des Points de Friction :** Repérer les biais habituels de l'IA (ex: code HTML/JS générique au lieu de `TableUI`, oubli de `AI_CONTEXT.md`) pour ériger des garde-fous.

---

## PARTIE 2 : ARCHITECTURE TECHNIQUE & CONFIGURATION (Le Hub Global & Local)

*Objectif : Structurer le dépôt centralisé de Skills et l'isoler des particularités de chaque projet pour éliminer toute duplication (Inspiration standards ouverts).*

### 2.1 Le Dépôt Maître Global (`skills-workspace/`)

* `catalog.yaml` : Registre central des Skills (inspiré des métadonnées `plugin.json`).
* `standards/` : Règles normatives transversales (`principles.md`, `pedagogy-referential.md`).
* `references/` : Connaissances techniques partagées (`stack/laravel.md`, `blade.md`, `alpine.md`, `database.md`, `architecture/layers-3tiers.md`).
* `templates/` : Modèles de départ (squelettes de `SKILL.md`).
* `.agents/skills/` : Les 9 Skills actionnables universels.
* `tests/` : Suites de tests comportementaux (6 cas par Skill) et fiches de retours YAML.
* `scripts/` : Outils de validation statique déterministes (Python).

### 2.2 L'Injection de Contexte Projet (ex: Active LMS)

* Dans le dépôt de l'application, création d'un dossier `.agents/local/` (versionné) :
* `project-conventions.md`
* `crud-backend-mechanic.md` (Mécanisme de filtres et de CRUD en PHP).
* `crud-frontend-ui.md` (**`TableUI`**, **`FormUI`** et exemples canoniques).


* Liaison du Hub via liens symboliques (`Junction` ou `Git Submodule`) vers `.agents/skills/` et `.agents/references/`.

---

## PARTIE 3 : RÉDACTION DES SKILLS (Le Modèle Unifié en 10 Rubriques)

*Objectif : Rédiger le code source (`SKILL.md`) de chaque Skill selon un format rigoureux respectant la **Divulgation Progressive** (*Progressive Disclosure*).*

### 3.1 Le Modèle Standard (`templates/generic-skill.md`)

1. **Frontmatter YAML :** `name`, `description` (formulée à la troisième personne et orientée sur l'action pour garantir un routage précis).
2. **# Mission :** Objectif précis.
3. **# Déclenchement :** Cas d'usage et exclusions.
4. **# Entrées et Ressources à Charger :** Instructions explicites de lecture des fichiers `local/` et `references/`.
5. **# Contraintes et Priorités :** Hiérarchie stricte (Priorité absolue au contexte local `.agents/local/`).
6. **# Procédure :** Les étapes ordonnées (adaptées à la conception, au dev ou à la pédagogie).
7. **# Décisions et Gestion des Ambiguïtés :** Conduite à tenir en cas de données manquantes (poser max 3 questions ou isoler les hypothèses).
8. **# Format de Sortie :** Structure exacte du livrable.
9. **# Contrôle Qualité :** Checklist binaire d'auto-validation.
10. **# Gestion des Erreurs & # Exemples :** Correction d'écarts et cas de test.

### 3.2 Le Dimensionnement Cible (Les 9 Skills Universels)

* **Conception (3) :** `analyser-et-planifier`, `concevoir-architecture`, `documenter-package` (génère les `AI_CONTEXT.md`).
* **Développement (4) :** `implementer-fonctionnalite` (gère les CRUD, filtres, `TableUI`/`FormUI`), `diagnostiquer-et-corriger`, `tester-et-valider`, `reviser-et-refactoriser`.
* **Pédagogie (2) :** `rediger-tutoriel`, `creer-atelier-pratique`.

---

## PARTIE 4 : CONTRÔLE QUALITÉ & TESTS COMPORTEMENTAUX

*Objectif : Valider rigoureusement les Skills avant leur publication pour éliminer ambiguïtés et contradictions.*

1. **Contrôles Statiques Automatisés :** Script Python (`scripts/validate-skills.py`) vérifiant la validité des fichiers YAML, Markdown, la correspondance des noms de dossiers et la présence des dépendances déclarées dans `catalog.yaml`.
2. **Matrice des Règles Normatives (Anti-Contradiction) :** Hiérarchie claire : `.agents/local/` (Priorité 1) > `references/architecture/` (Priorité 2) > `references/stack/` (Priorité 3).
3. **Suite de Tests Comportementaux (6 Cas Types) :** Validation de chaque Skill sur le cas nominal, le cas incomplet, le cas ambigu, le cas hors périmètre, le cas contradictoire et la non-régression.

---

## PARTIE 5 : MAINTENANCE ET CYCLE DE VIE (Évolution Contrôlée)

*Objectif : Faire évoluer la bibliothèque de Skills sans casser l'existant.*

1. **Enregistrement des Retours (Fiches d'Incidents YAML) :** Documentation de chaque anomalie sous forme de fiche YAML dans `tests/feedbacks/` classée par type (`lacune`, `contradiction`, `ambiguite`, etc.).
2. **Versionnage Sémantique (SemVer) :** Application rigoureuse des patches (`1.0.1`), versions mineures (`1.1.0`) et majeures (`2.0.0`) gérées via le `changelog.md`.
3. **Archivage :** Passage du statut à `deprecated` puis déplacement dans `archive/` si nécessaire.

---

## PARTIE 6 : PLAN DE MISE EN ŒUVRE EN 4 PHASES (Feuille de Route)

| Phase | Objectif | Actions Concrètes à Réaliser | Critère de Réussite (Livrable) |
| --- | --- | --- | --- |
| **Phase 1 : Poser le Socle Unifié** *(Jours 1–2)* | Créer le dépôt maître et les règles communes sans coder d'outils complexes. | 1. Créer le dépôt Git `skills-workspace` avec `catalog.yaml`, `standards/` (incluant `pedagogy-referential.md`), `references/` (Laravel, Blade, Alpine, SQL, Architecture 3-tiers) et `templates/`.<br>

<br>2. Créer dans Active LMS le dossier `.agents/local/` avec `crud-frontend-ui.md` (`TableUI`/`FormUI`) et `crud-backend-mechanic.md`. | Le dépôt `skills-workspace` est structuré et relié à Active LMS via lien symbolique (`Junction` ou `submodule`). |
| **Phase 2 : Valider 3 Skills Pilotes** *(Jours 3–5)* | Éprouver un Skill par catégorie sur des cas réels (Inspiration *Claude Plugins* / *Agent Skills*). | Créer et tester sur vos vrais projets :<br>

<br>1. `concevoir-architecture` (Conception)<br>

<br>2. `implementer-fonctionnalite` (Dev)<br>

<br>3. `rediger-tutoriel` (Pédagogie).<br>

<br>Corriger les lacunes des modèles de départ suite aux premiers essais. | 3 Skills en statut `active` ayant réussi leurs 6 cas de test (ex: ajout d'un filtre `TableUI` et rédaction de son tutoriel). |
| **Phase 3 : Automatiser la Qualité** *(Semaine 2)* | Bloquer les erreurs mécaniques avant publication. | 1. Activer `Markdownlint` et `Yamllint` dans l'IDE.<br>

<br>2. Mettre en place `scripts/validate-skills.py` et `check-links.py`.<br>

<br>3. Créer le Skill `documenter-package` pour générer les fichiers `AI_CONTEXT.md` de vos packages Active LMS existants. | Les scripts valident 100 % du dépôt sans erreur et chaque package principal d'Active LMS possède son `AI_CONTEXT.md`. |
| **Phase 4 : Étendre et Maintenir** *(Continu)* | Compléter la bibliothèque jusqu'aux 8–9 Skills cibles selon les besoins réels. | 1. Ajouter progressivement les autres Skills (`analyser-et-planifier`, `diagnostiquer-et-corriger`, etc.).<br>

<br>2. Appliquer strictement le cycle de fiches de retour YAML et le versionnement SemVer. | Une bibliothèque stable de 8 à 10 Skills partagés entre tous vos projets, sans doublon ni dérive. |