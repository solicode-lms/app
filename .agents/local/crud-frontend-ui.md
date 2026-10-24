# Mécanique Frontend UI : Solicode LMS

> **PRIORITÉ 1 (Contexte Local)**  
> Règles opérationnelles pour les composants TableUI, FormUI, FilterUI et vues Blade de Solicode LMS.

## 1. Structure des Vues Blade
Chaque entité dans `modules/Pkg.../resources/views/[model]/` possède :
- Vues de base générées par Gapp (lockées avec `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}`) :
  - `_table.blade.php` : Corps du tableau.
  - `_fields.blade.php` : Champs du formulaire.
  - `_index.blade.php` : Structure générale de l'index avec barre de filtres et stats.
  - `_show.blade.php` : Affichage détaillé.
- **Sous-dossier `custom/` (Zone d'extension) :**
  - Contient les vues modifiables par le développeur/l'agent qui étendent les vues de base :
  - Ex: `custom/_table.blade.php` :
    ```blade
    @extends('PkgFormation::filiere._table')
    @section('filiere-table-tbody')
        <!-- Personnalisation des lignes sans toucher au fichier de base -->
    @endsection
    ```

## 2. Framework JavaScript (`resources/js/crud/`)
- **`TableUI.js` :** Orchestrateur principal.
  - Classes réservées : `showIndex`, `showEntity`, `editEntity`, `deleteEntity`.
  - Attributs data : `data-id`, `data-field`, `data-context-key`.
- **`FilterUI.js` :** Gestion des filtres.
  - Déclencheur automatique de rechargement asynchrone après 500ms de pause dans la saisie.
  - Détection automatique de `data-target-dynamic-dropdown` pour les sélecteurs en cascade.
- **`FormUI.js` & `DataCalculTreatment.js` :**
  - Attribut `data-calcule` sur les champs déclencheurs :
    ```blade
    <input type="number" name="prix_unitaire" data-calcule="total" class="form-control">
    <input type="number" name="quantite" data-calcule="total" class="form-control">
    <input type="text" name="total" readonly class="form-control">
    ```

## 3. Modales Imbriquées
- Gérées via `iziModal` et la configuration globale `window.crudModalManagersConfig`.
- Permet d'ouvrir une modale d'édition depuis un tableau déjà affiché dans une modale sans perdre le contexte parent.
