# Nouvelle installation de SoliLMS 

## Création de la base de données 

```bash
php artisan migrate:fresh
```

Laravel va automatiquement :

1. **Recharger le schéma** depuis `database/schema/mysql-schema.dump`,
2. Puis exécuter uniquement les **migrations créées après le dump**.

## Seeder des données 

On peut exécuter les seeder Module par Module pendant le développement et validation des seeders en modifiant le fichiet 

modules-config.json


les modules oblégatoir : 

- Core
- PkgAutorisation
- PkgWidgets