# Référence : Framework Frontend TableUI & FilterUI

## 1. Vue d'Ensemble (`resources/js/crud/`)
L'interface utilisateur de Solicode LMS s'appuie sur une suite de modules ES6 pour gérer les tableaux et les formulaires asynchrones sans rechargement de page :
- **`TableUI.js`** : Gestion des tableaux dynamiques, pagination asynchrone, tri multi-colonnes, suppression groupée (`BulkAction`), et édition en ligne (`CellOrchestrator`).
- **`FilterUI.js`** : Écoute du formulaire de filtre avec debounce (500ms), adaptation au contexte (`adapterPourContext`), et déclenchement de listes déroulantes dépendantes.
- **`ModalUI.js` & `CrudModalManager.js`** : Affichage et empilement des fenêtres modales asynchrones via `iziModal`.
- **`FormUI.js`** : Soumission AJAX, gestion des calculs dynamiques (`data-calcule`) et affichage des messages d'erreur.

---

## 2. Interception AJAX & Classes Réservées aux Modales
Le framework intercepte automatiquement les événements de clic sur les éléments porteurs de classes CSS réservées :

| Classe CSS | Action Frontend Déclenchée |
| :--- | :--- |
| **`showIndex`** | Ouvre la page de listing ciblée dans une fenêtre modale asynchrone. |
| **`showEntity`** | Charge la vue détaillée (`show`) de l'enregistrement dans la modale. |
| **`editEntity`** | Charge le formulaire d'édition (`edit`) dans la modale. |
| **`createEntity`** | Ouvre le formulaire de création (`create`) dans la modale. |
| **`deleteEntity`** | Déclenche la confirmation de suppression SweetAlert2 avec requête DELETE asynchrone. |
| **`context-state`** | Propage l'état du contexte (`contextKey`) dans la requête AJAX. |

> [!WARNING]
> **Interdiction de Conflits d'Événements :**
> Ne jamais attacher d'écouteurs jQuery directs (`$('...').on('click')`) sur ces classes réservées. Laisser `CrudModalManager` gérer la délégation d'événements.

---

## 3. Modales Imbriquées (`window.crudModalManagersConfig`)
Lorsqu'une modale doit s'ouvrir au-dessus d'une autre (ex: éditer un livrable depuis la modale d'une tâche) :
- Le framework empile les instances d'`iziModal`.
- Pour initialiser le gestionnaire sur un conteneur personnalisé, injecter la configuration dans la vue Blade parente :
```blade
@push('scripts')
<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        containerSelector: '#monConteneurCustom',
        defaultModel: 'Tache',
        contextKey: 'tache-modal-context'
    });
</script>
@endpush
```

---

## 4. Filtrage Asynchrone & Dynamic Dropdowns
Pour lier une liste déroulante dépendante dans un formulaire ou filtre :
- Ajouter l'attribut `data-target-dynamic-dropdown` sur le select parent avec le sélecteur du select enfant cible.
- Fournir la route AJAX qui renvoie le JSON des options filtrées :
```blade
<select name="etablissement_id" 
        class="form-control select2"
        data-target-dynamic-dropdown="#filiere_id"
        data-dynamic-url="{{ route('filieres.byEtablissement') }}">
</select>
```
- `FilterUI.js` écoute l'événement `change` et peuple automatiquement `#filiere_id`.

---

## 5. Vues Blade et Personnalisation
Pour personnaliser l'affichage sans modifier les templates cœurs générés par Gapp :
- Créer ou surcharger la vue dans le sous-dossier `custom/` (ex: `resources/views/[model]/custom/_table.blade.php`).
- Étendre la vue de base avec `@extends('Pkg...::[model]._table')` et surcharger les sections Blade prévues (`@section('[model]-table-tbody')`, etc.).
