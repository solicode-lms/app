# Règles Spécifiques - Agent Présentation

> Ce fichier est ta mémoire évolutive. Il contient les règles strictes pour la couche présentation.

- [Règle initiale] : Toujours utiliser la syntaxe Blade (`{{ $var }}`) pour l'affichage des variables.
- [Règle initiale] : Garder les contrôleurs "Skinny" (minces). Toute logique métier > 5 lignes va dans un Service.

## Structure UI CRUD (Standard)
Les vues doivent impérativement suivre cette structure modulaire pour garantir la maintenabilité et l'uniformité :

### 1. Index (`index.blade.php`)
- **Rôle** : Point d'entrée principal.
- **Contenu** : Squelette de la page, inclusion des scripts, gestion des modales.
- **Inclusion** : Appelle `_table.blade.php` pour la liste.

### 2. Table (`_table.blade.php`)
- **Rôle** : Tableau de données (DataGrid).
- **Contenu** : Headers, boucle `foreach`, pagination.
- **Composants** : Utilise `<x-sortable-column>`, `<x-checkbox-row>`, `<x-action-button>`.

### 3. Show (`_show.blade.php`)
- **Rôle** : Vue détail d'une entité.
- **Contenu** : Affichage groupé par sections (Cards).
- **Extensibilité** : Peut inclure des vues partielles pour les relations (HasMany).

### 4. Fields (`_fields.blade.php`)
- **Rôle** : Formulaire partagé (Create/Edit).
- **Contenu** : Champs de saisie (Inputs, Selects).

### 5. Custom (`custom/`)
- **Dossier** : `resources/views/{Entity}/custom/`
- **Usage** :
    - `custom/fields/{field}.blade.php` : Affichage spécifique d'une colonne dans la table (ex: badge, mise en forme complexe).
    - `custom/forms/{field}.blade.php` : Input spécifique dans le formulaire.
- **Règle** : Ne jamais modifier les fichiers générés (`_table`, `_fields`) pour une logique spécifique à une seule colonne ; créer une vue `custom` et l'inclure.
