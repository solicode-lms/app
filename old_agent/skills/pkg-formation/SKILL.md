---
name: pkg-formation
description: Expert du module PkgFormation — gestion des formateurs, filières, modules, spécialités, années de formation et relations pédagogiques avec les groupes.
---

# Skill : Expert Module PkgFormation

## 🎯 Périmètre Global
**Mission** : Fournir une expertise complète sur le module `PkgFormation` qui structure le cadre académique et pédagogique de Solicode LMS : les formateurs, les filières, les années de formation, les modules de formation et les spécialités.

### 🚫 Interdictions & Règles d'Or
1. **Intégrité Gapp** : Ne jamais modifier les classes situées dans `Base/` (ex: `BaseFormateur`, `BaseFiliere`). Surcharger uniquement dans les classes filles.
2. **Cloisonnement Formateur** : Un formateur n'a autorité pédagogique que sur les groupes qui lui sont formellement assignés via la table pivot `formateur_groupe` et les projets dont il est référent (`formateur_id`).
3. **Synchronisation Utilisateur** : Tout formateur créé doit posséder un compte `User` avec le rôle Spatie `formateur` (géré dans `FormateurService::create`).

---

## 🏗️ Architecture des Modèles et Relations

### 1. Entités Principales
- **`Formateur` (`formateurs`)** :
  - `matricule`, `nom`, `prenom`, `email`, `tele_num`, `user_id`.
  - Relations :
    - `user()` : `belongsTo(User::class, 'user_id')`.
    - `groupes()` : `belongsToMany(Groupe::class, 'formateur_groupe', 'formateur_id', 'groupe_id')`.
    - `specialites()` : `belongsToMany(Specialite::class, 'formateur_specialite')`.
    - `projets()` : `hasMany(Projet::class, 'formateur_id')`.
    - `evaluateur()` : `hasOne(Evaluateur::class)`.
- **`Filiere` (`filieres`)** :
  - `code`, `nom`, `description`.
  - Relations : `groupes()`, `modules()`.
- **`AnneeFormation` (`annees_formation`)** :
  - `titre`, `date_debut`, `date_fin`, `reference`.
  - Relations : `groupes()`.
- **`Module` (`modules`)** :
  - Modules de formation rattachés aux compétences et filières.

### 2. Le Pivot Formateur <-> Groupe (`formateur_groupe`)
- Table pivot : `formateur_groupe` (`formateur_id`, `groupe_id`).
- Un formateur peut enseigner à plusieurs groupes.
- Un groupe peut avoir plusieurs formateurs intervenants.
- **Requête des groupes d'un formateur** :
  ```php
  $groupes = $formateur->groupes()->orderBy('nom')->get();
  ```

---

## ⚡ Services et Logique Métier

### `FormateurService`
- **Méthode pivot `getApprenants($formateur_id)`** :
  Récupère l'ensemble des apprenants inscrits dans les groupes enseignés par le formateur :
  ```php
  public function getApprenants($formateur_id)
  {
      return Apprenant::whereHas('groupes', function ($query) use ($formateur_id) {
          $query->whereHas('formateurs', function ($q) use ($formateur_id) {
              $q->where('formateurs.id', $formateur_id);
          });
      })->get();
  }
  ```
- **Gestion des mots de passe & comptes** : `initPassword(int $formateurId)`.

---

## 🔗 Relations Pédagogiques Transverses

| Acteur / Entité | Relation | Règle Métier |
| :--- | :--- | :--- |
| **Formateur -> Groupe** | `belongsToMany` via `formateur_groupe` | Définit le périmètre d'intervention du formateur. |
| **Formateur -> Apprenant** | Traversée via `groupes.apprenants` | Un formateur ne peut superviser que les apprenants de ses groupes assignés. |
| **Formateur -> Projet** | `hasMany` via `projets.formateur_id` | Projets créés ou dirigés par ce formateur. |
