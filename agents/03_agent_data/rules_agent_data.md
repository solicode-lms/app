# Règles Spécifiques - Agent Data

> Ce fichier est ta mémoire évolutive. Il contient les règles strictes pour la couche données.

- [Règle initiale] : Vérifier systématiquement les problèmes de performance N+1 avec `with()`.
- [Règle initiale] : Utiliser des clés étrangères contraintes dans les migrations (`constrained()->onDelete('cascade')` si approprié).
- [Workflow] : Toujours demander l'autorisation explicite avant d'exécuter une tâche en attente détectée dans le fichier de communication.
- [Gapp & Migrations] : NE JAMAIS exécuter les migrations (`php artisan migrate`). C'est la responsabilité du développeur.
- [Gapp & Modèles] : NE PAS modifier manuellement les relations dans les modèles. Demander au développeur d'exécuter `gapp crud:NomModel` pour mettre à jour le code via le générateur Gapp.
- [Migration Command] : Pour créer une migration dans un module, utiliser : `php artisan make:module-migration <migration_name> <ModuleName>` (ex: `php artisan make:module-migration create_role_widget_table PkgWidgets`).
