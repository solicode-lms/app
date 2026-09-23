# Plan de Création et de Gestion des Skills Agent

> **Date** : 2026-09-23
> **Objectif** : Structurer les skills de l'agent en deux catégories (Couche applicative vs Package métier) pour couvrir l'ensemble du projet Solicode LMS de façon modulaire.

## 1. Catégorisation des Skills Existants

Actuellement, l'agent dispose de plusieurs skills qui se répartissent comme suit :

### Skills de la Couche Applicative (Transverses)
Ces skills sont conservés en l'état car ils gèrent des technologies ou des standards architecturaux communs à tous les packages.
- `app-blade` : Architecture et personnalisation des vues.
- `app-service-layer` : Couche Service, traits et règles.
- `app-create-table` : Création de tables et migrations.
- `app-view-state` : Manipulation et filtres du ViewState.
- `app-inline-edit` : Configuration de l'édition en ligne.
- `app-filtre` : Ajout et configuration des filtres.
- `app-gapp-metadata` : Configuration JSON Gapp.
- `app-db-savoir` : Exploration locale de la base de données.

### Skills Utilitaires (Hors scope métier/applicatif)
- `sys-agent` : Méta-skill de gestion de l'agent.
- `sys-deploy-update` : Mises à jour des données (seeding, data).
- `sys-redacteur-technique` : Documentation technique dans `docs/`.

### Skills par Package (Métier)
Ces skills sont dédiés à la logique métier d'un module (Package) spécifique.
- `pkg-apprentissage` : Expert dédié au module `PkgApprentissage`.
- `pkg-mobilisation-affectation` : *À analyser et potentiellement renommer* (voir section 2).

---

## 2. Skills à Renommer ou Merger

- **`pkg-mobilisation-affectation`** : Ce skill couvre une logique fonctionnelle liée aux affectations de projets et unités d'apprentissage. Il devrait idéalement être renommé ou fusionné pour s'aligner sur la logique "Un skill = Un package".
  - **Action proposée** : Renommer en `pkg-creation-projet` (ou `pkg-realisation-projet` selon le périmètre exact) s'il dépend de ces modules, ou le fusionner dans un nouveau skill global pour ce module.

---

## 3. Skills à Créer (Feuille de Route)

L'objectif est d'avoir **un expert métier par package**. Voici la liste des packages actuels ne disposant pas encore d'un expert attitré, et pour lesquels un Skill doit être généré (via le workflow `expert-agent`) :

1.  **`pkg-core`** : Rôle pour gérer le noyau de l'application (SysModules, etc.).
2.  **`pkg-apprenants`** : Gestion métier des apprenants (PkgApprenants).
3.  **`pkg-autorisation`** : Gestion des utilisateurs, rôles et permissions (PkgAutorisation).
4.  **`pkg-competences`** : Référentiels de compétences (PkgCompetences).
5.  **`pkg-creation-projet`** : (Si `pkg-mobilisation-affectation` n'est pas utilisé pour cela).
6.  **`pkg-creation-tache`** : Workflow de tâches (PkgCreationTache).
7.  **`pkg-evaluateurs`** : (PkgEvaluateurs).
8.  **`pkg-formation`** : Organisation pédagogique, filières (PkgFormation).
9.  **`pkg-qcm`** : Gestion des QCM, questions, affectations (PkgQcm).
10. **`pkg-realisation-projets`** : Suivi des projets côté apprenant (PkgRealisationProjets).
11. **`pkg-realisation-tache`** : (PkgRealisationTache).
12. **`pkg-sessions`** : (PkgSessions).
13. **`pkg-statistiques`** : (PkgStatistiques).

---

## 4. Protocole de Création (Rappel)

Pour créer un de ces nouveaux skills métiers, il faut utiliser la commande / workflow approprié :
1. L'agent analyse le module (ex: `PkgQcm`).
2. L'agent utilise `sys-agent` pour instancier le nouveau skill (ex: `pkg-qcm`).
3. Le skill doit documenter la structure des données, les règles de calcul spécifiques et les particularités Blade de ce module.
