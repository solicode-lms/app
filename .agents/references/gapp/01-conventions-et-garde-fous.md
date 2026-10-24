# Référence Gapp : Conventions & Garde-Fous

## 1. Statut et Rôle de Gapp
Gapp est le générateur de code interne utilisé sur Solicode LMS. Il génère automatiquement :
- Les classes de base Eloquent (`Base/Base[Model].php`).
- Les services de base (`Base/Base[Model]Service.php`).
- Les contrôleurs de base (`Base/Base[Model]Controller.php`).
- Les vues Blade CRUD (`_table.blade.php`, `_fields.blade.php`, `_index.blade.php`, `_show.blade.php`).
- Les seeders de permissions Spatie.

## 2. Règles de Protection (Locks Gapp)
- **Fichiers locked intouchables :** Tout fichier portant la mention `// Ce fichier est maintenu par ESSARRAJ Fouad` ou `{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}` ne doit JAMAIS être modifié directement dans le cadre du développement applicatif standard.
- **Classes enfants PHP :** Le développeur ou l'agent supprime la mention de protection sur la classe enfant (`[Model].php` ou `[Model]Service.php`) pour y insérer son code métier.
- **Vues Blade :** Utiliser systématiquement le dossier `custom/`.
