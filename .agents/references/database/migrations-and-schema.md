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

## 2. Inscription dans `db_schemas/modules.json`
Lors de la création d'une nouvelle table rattachée à un package modulaire, la table doit être déclarée sous son package respectif dans `db_schemas/modules.json` pour permettre à Gapp de la reconnaître lors de la commande `gapp meta:sync`.
