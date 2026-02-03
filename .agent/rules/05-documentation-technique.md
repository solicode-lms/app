# 📘 Documentation Technique

## 1. Structure d'un Module (Standard)
```
modules/NomDuModule/
├── Config/
├── Database/ (Migrations, Seeders)
├── Entities/ ou Models/
├── Http/ (Controllers, Requests)
├── Providers/
├── Resources/ (views, lang)
├── Routes/ (web.php, api.php)
├── Services/ (Base, NomModelService.php)
└── module.json
```

## 2. Champs Calculables (Gapp)
Pour rendre un attribut virtuel triable/filtrable :
- Ajouter `public $calculable = true;` dans le modèle ou le service (selon implémentation locale).
- Définir la requête SQL brute pour le tri.

## 3. Widgets (PkgWidgets)
Configuration via table `widgets`, colonne `parameters` (JSON) :
- `link`: Route vers laquelle pointe le widget.
- `roles`: Visibilité/Config par rôle (admin, formateur...).
- `dataSource`: Méthode du Service à appeler pour la valeur (ex: `ApprenantService::countActive`).
- `conditions`: Filtres SQL additionnels.

*Refresh* : Après modif, utiliser `WidgetUtilisateurService::syncWidgetsFromRoles()`.

## 4. Maintenance Bases de Données
- Les dumps SQL sont stockés dans `backup_db/`.
- Toujours vérifier `db_schemas/` pour les références de structure.
