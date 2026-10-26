# Mécanique Frontend UI : Solicode LMS

> **PRIORITÉ 1 (Contexte Local)**  
> Guides d'implémentation pour les composants TableUI, FormUI, FilterUI et les vues Blade.
> Les contraintes invariables de protection des templates générés sont régies par [.agents/rules/critical-rules.md](file:///c:/AppServer/solicode-lms/.agents/rules/critical-rules.md).

## 1. Extension des Vues Blade (Sous-dossier `custom/`)
Toute personnalisation d'affichage s'opère dans `modules/Pkg.../resources/views/[model]/custom/` en étendant le template de base correspondant :

### Personnalisation du Tableau (`custom/_table.blade.php`)
```blade
@extends('PkgFormation::filiere._table')

@section('filiere-table-tbody')
    <!-- Personnalisation des lignes ou injection de colonnes spécifiques -->
@endsection
```

### Personnalisation des Champs de Formulaire (`custom/_fields.blade.php`)
```blade
@extends('PkgFormation::filiere._fields')

@section('filiere-fields-extra')
    <!-- Champs calculés ou sélecteurs personnalisés -->
@endsection
```

### Personnalisation du Listing et Filtres (`custom/_index.blade.php`)
```blade
@extends('PkgFormation::filiere._index')

@section('filiere-index-extra-actions')
    <!-- Boutons d'actions groupées ou filtres additionnels -->
@endsection
```

## 2. Framework JavaScript (`resources/js/crud/`)

### `TableUI.js` (Orchestration du Tableau)
- Classes CSS conventionnelles pour les déclencheurs :
  - `showIndex` : Rafraîchissement ou affichage de l'index.
  - `showEntity` : Consultation modale d'un enregistrement.
  - `editEntity` : Ouverture du formulaire d'édition.
  - `deleteEntity` : Suppression avec confirmation.
- Attributs HTML d'identification :
  - `data-id` : Identifiant de l'enregistrement.
  - `data-field` : Nom du champ ciblé pour modification inline.
  - `data-context-key` : Clé de contexte pour synchronisation avec le ViewState.

### `FilterUI.js` (Filtrage Réactif & Dynamique)
- Rechargement asynchrone automatique temporisé (debounce de 500ms sur la saisie textuelle).
- **Listes Déroulantes en Cascade :**
  Utiliser l'attribut `data-target-dynamic-dropdown` pour lier un sélecteur maître à un sélecteur esclave :
  ```html
  <select name="filiere_id" data-target-dynamic-dropdown="[name='groupe_id']">
  ```

### `FormUI.js` & `DataCalculTreatment.js` (Calculs Côté Vue)
- Déclenchement automatique de recalcul via l'attribut `data-calcule` :
  ```blade
  <input type="number" name="prix_unitaire" data-calcule="total" class="form-control">
  <input type="number" name="quantite" data-calcule="total" class="form-control">
  <input type="text" name="total" readonly class="form-control">
  ```

## 3. Gestion des Modales Imbriquées
- S'appuie sur `iziModal` et la configuration globale `window.crudModalManagersConfig`.
- Permet d'ouvrir une modale d'édition ou de création depuis un tableau déjà contenu dans une modale parent sans perdre le contexte d'origine.
