# Référence : Base de Données, Migrations et Schéma

## 1. Règles d'Or pour les Migrations
1. **L'agent n'exécute JAMAIS `php artisan migrate` :**
   L'agent se contente de générer le code du fichier de migration dans `modules/Pkg[Nom]/Database/Migrations/`, de mettre à jour `db_schemas/modules.json`, puis demande au développeur de lancer la commande.
2. **Champ `reference` Obligatoire :**
   Toute nouvelle table principale doit impérativement comporter une colonne :
   ```php
   $table->string('reference')->unique();
   ```
   Ce champ est indispensable pour l'idempotence des seeders et l'identification des enregistrements par Gapp.
3. **Clés Étrangères et Cascade :**
   Toujours spécifier explicitement le comportement `onDelete('cascade')` ou `onDelete('set null')` sur les relations.
   Toujours nettoyer les contraintes de clés étrangères dans la méthode `down()`.

## 2. Commande de Création de Migration
Pour générer une nouvelle migration directement dans le bon package modulaire :
```bash
php artisan make:module-migration <nom_de_la_migration> <NomDuModule>
# Exemple : php artisan make:module-migration create_role_widget_table PkgWidgets
```

---

## 3. Inscription dans `db_schemas/modules.json`
Lors de la création d'une nouvelle table rattachée à un package modulaire, la table doit être déclarée sous son package respectif dans `db_schemas/modules.json` pour permettre à Gapp de la reconnaître lors de la commande `gapp meta:sync`.

---

## 4. Séquence Post-Migration Obligatoire
Après modification de la structure de la base de données, le développeur doit suivre cette séquence stricte :
1. `php artisan migrate` (appliquée par le développeur).
2. `gapp meta:sync` (synchronise le dictionnaire de données Gapp).
3. `gapp make:crud NomModel` (régénère les classes `Base/` associées).
