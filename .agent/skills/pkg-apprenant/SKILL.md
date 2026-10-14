---
name: pkg-apprenant
description: Expert du module PkgApprenants — gestion des apprenants, des groupes, des sous-groupes, du scope actif et des relations avec les formateurs et projets.
---

# Skill : Expert Module PkgApprenants

## 🎯 Périmètre Global
**Mission** : Fournir une expertise complète sur le module `PkgApprenants`, responsable de la gestion des apprenants, de leurs groupes d'appartenance, de leur statut d'activité (`actif`), et de leurs liaisons avec les formateurs et les réalisations de projets.

### 🚫 Interdictions & Règles d'Or
1. **Intégrité Gapp** : Ne jamais modifier les classes situées dans `Base/` (`BaseApprenant`, `BaseGroupe`).
2. **Scope Global `inactif`** : Le modèle `Apprenant` possède un scope global `inactif` qui filtre automatiquement par défaut `where('actif', true)`. Pour inspecter ou lister sans restriction, utiliser explicitement `Apprenant::withoutGlobalScope('inactif')`.
3. **Partitionnement & Cloisonnement** :
   - Un apprenant ne peut consulter que ses propres données et réalisations.
   - Un formateur ne peut consulter que les apprenants des groupes auxquels il est assigné via `formateur_groupe`.
   - Seul le Responsable / Admin a un accès transverse à l'ensemble des groupes et apprenants.

---

## 🏗️ Architecture des Modèles et Relations

### 1. Modèle `Apprenant` (`apprenants`)
- **Attributs clés** : `matricule`, `nom`, `prenom`, `email`, `actif`, `user_id`, `date_inscription`.
- **Relations clés** :
  - `user()` : `belongsTo(User::class, 'user_id')`.
  - `groupes()` : `belongsToMany(Groupe::class, 'apprenant_groupe', 'apprenant_id', 'groupe_id')`.
  - `sousGroupes()` : `belongsToMany(SousGroupe::class, 'apprenant_sous_groupe')`.
  - `realisationProjets()` : `hasMany(RealisationProjet::class, 'apprenant_id')`.

### 2. Modèle `Groupe` (`groupes`)
- **Attributs clés** : `code`, `nom`, `description`, `filiere_id`, `annee_formation_id`.
- **Relations clés** :
  - `apprenants()` : `belongsToMany(Apprenant::class, 'apprenant_groupe', 'groupe_id', 'apprenant_id')`.
  - `formateurs()` : `belongsToMany(Formateur::class, 'formateur_groupe', 'groupe_id', 'formateur_id')`.
  - `filiere()` : `belongsTo(Filiere::class, 'filiere_id')`.
  - `anneeFormation()` : `belongsTo(AnneeFormation::class, 'annee_formation_id')`.
  - `projets()` : `hasMany(Projet::class, 'groupe_id')`.
  - `affectationProjets()` : `hasMany(AffectationProjet::class, 'groupe_id')`.

---

## ⚡ Requêtes Métier & Patterns Fréquents

### 1. Apprenants d'un Formateur
```php
// Tous les apprenants des groupes du formateur
$apprenants = Apprenant::whereHas('groupes.formateurs', function ($q) use ($formateurId) {
    $q->where('formateurs.id', $formateurId);
})->orderBy('nom')->get();
```

### 2. Apprenants d'un Groupe Spécifique
```php
$apprenants = $groupe->apprenants()
    ->where('actif', true)
    ->orderBy('nom')
    ->get();
```

### 3. Contrôle d'Accès Formateur -> Apprenant
Vérifier si un apprenant donné est bien sous la supervision du formateur connecté :
```php
$isAllowed = $formateur->groupes()
    ->whereHas('apprenants', function ($q) use ($apprenantId) {
        $q->where('apprenants.id', $apprenantId);
    })->exists();

if (!$isAllowed) {
    abort(403, "Accès refusé. Cet apprenant ne fait pas partie de vos groupes.");
}
```

---

## 🔗 Matrice des Droits d'Accès aux Tableaux de Bord

| Rôle Utilisateur | Groupes Accessibles | Apprenants Affichables |
| :--- | :--- | :--- |
| **Apprenant** | Son groupe actif uniquement | Son propre profil uniquement (aucun sélecteur) |
| **Formateur** | Ses groupes assignés (`formateur_groupe`) | Ses apprenants uniquement (filtrables par groupe) |
| **Responsable / Admin** | Tous les groupes de l'établissement | Tous les apprenants de l'établissement |
