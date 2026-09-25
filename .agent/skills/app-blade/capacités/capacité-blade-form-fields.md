# Capacité : Personnalisation des Saisies (Forms)

## 1. Rôle et Emplacement
Le dossier `custom/forms/` permet l'adaptation granulaire du comportement HTML des formulaires d'édition ou de création `_fields.blade.php` (champ par champ).
- **Chemin cible** : `custom/forms/[nom_du_champ].blade.php`

## 2. Règle Critique sur `<x-form-field>`
**La résolution est dynamique : Pas de régénération nécessaire !**
- Dans `_fields.blade.php`, chaque champ est entouré par un `use App\View\Components\FormField`.
- L'appel se fait via `<x-form-field :entity="$item" field="titre" :bulkEdit="$bulkEdit">`.
- Classe `FormField.php` : Ce composant va automatiquement vérifier à l'exécution de la page (Runtime Laravel) si le fichier `custom.forms.titre` existe. Si oui, il le charge ; sinon, il rend le champ par défaut inclus dans le bloc `<x-form-field>`.
- **Action de Création** : Il suffit de créer le fichier `.blade.php` avec le HTML du champ souhaité (`<input>`, `<select>`, attributs `wire:model`, classes custom). L'effet est immédiat au rafraîchissement.
