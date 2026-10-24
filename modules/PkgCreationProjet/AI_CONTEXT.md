# Contexte IA du Module : PkgCreationProjet

## 1. Responsabilité Métier
Le package **PkgCreationProjet** constitue le cœur de l'ingénierie des projets pédagogiques au sein de Solicode LMS :
1. **Conception des Projets :** Création, paramétrage et modélisation des projets pédagogiques (`Projet`), rattachés à une `Filiere` et pilotés par un `Formateur`.
2. **Spécification des Livrables :** Définition des attendus techniques (`Livrable`) et de leur typologie (`NatureLivrable` : dépôt Git, lien URL, document, archive).
3. **Ressources Didactiques :** Association de supports pédagogiques (`Resource`).
4. **Constitution des Équipes :** Structuration des équipes de travail par projet (`EquipeProjet`).
5. **Mobilisation des Compétences :** Alignement avec les Unités d'Apprentissage (`MobilisationUa`).

---

## 2. Modèles & Relations Principales

### `Projet` (Table : `projets`)
- **Hérite de :** `BaseProjet`
- **Champs clés :** `titre`, `description`, `objectifs`, `filiere_id`, `formateur_id`, `date_debut`, `date_fin`, `is_session_formation`, `reference`.
- **Eager Loading par défaut :** `filiere`, `formateur`.
- **Relations directes :**
  - `filiere` : `belongsTo(Filiere::class, 'filiere_id')` *(PkgFormation)*.
  - `formateur` : `belongsTo(Formateur::class, 'formateur_id')` *(PkgFormation)*.
  - `livrables` : `hasMany(Livrable::class, 'projet_id')`.
  - `resources` : `hasMany(Resource::class, 'projet_id')`.
  - `equipeProjets` : `hasMany(EquipeProjet::class, 'projet_id')`.
- **Relations inter-modules :**
  - `taches` : `hasMany(Tache::class, 'projet_id')` *(PkgCreationTache)*.
  - `affectationProjets` : `hasMany(AffectationProjet::class, 'projet_id')` *(PkgRealisationProjets)*.
- **Scopes Métier :** `scopeSessionsFormation()` filtre les projets intégrant des épreuves de Live Coding / Prototype N2.

### `Livrable` (Table : `livrables`) & `NatureLivrable` (Table : `nature_livrables`)
- Modélisation des types de dépôts attendus (code source GitHub, documentation PDF, URL d'hébergement).

---

## 3. Services & Logique Métier

### `ProjetService` (`Modules\PkgCreationProjet\Services\ProjetService`)
- **Duplication & Clonage :** Duplication complète d'un projet avec ses tâches, livrables et ressources pour une nouvelle promotion.
- **Gestion des Droits Formateurs :** Restreint la modification des projets aux seuls formateurs propriétaires ou administrateurs.
- **Calculs Dynamiques :** Recalcul automatique de la masse horaire globale et de la durée cumulée des tâches associées.

---

## 4. Particularités UI & Filtres
- **Filtres TableUI :** Filtrage ManyToOne par Filière (`filiere_id`) et Formateur (`formateur_id`).
- **Composants d'Interface :** Vue d'ensemble par cartes/projets, onglets de navigation par phase et modales de gestion des livrables.
