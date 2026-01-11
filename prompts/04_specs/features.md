# ⚙️ Spécifications Fonctionnelles et Techniques

## 1. Structure d'un Module
Structure standard à respecter lors de la création ou modification de fichiers :
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

## 2. Champs Calculables
- Pour ajouter un champ calculable :
  - Ajouter attribut `calculable = true`.
  - Définir la **requête SQL** permettant le tri/recherche.
  - Exemple : `SELECT count(*) FROM ... WHERE ...`

## 3. Widgets (PkgWidgets)
- Les widgets sont définis en JSON dans la colonne `parameters` de la table `widgets`.
- Structure JSON :
  - `link`: Route cible.
  - `roles`: Paramètres spécifiques par rôle (admin, apprenant...).
  - `dataSource`: Méthode de service pour les données.
  - `conditions`: Filtres directs.
- Utiliser `WidgetUtilisateurService::syncWidgetsFromRoles()` après modification.

## 4. Tâches Courantes (Guide)
- **Création Table** : Utiliser `make:module-migration`.
- **Ajout Méthode Service** : Voir `coding_styles.md` pour l'architecture, implémenter dans `Services/NomService.php`.
- **Ajout Widget** : Insérer en BDD avec JSON de config.
