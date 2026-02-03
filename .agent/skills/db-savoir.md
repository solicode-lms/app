---
name: db-savoir
description: Competence pour explorer la structure de la base de données et comprendre les relations entre les tables.
---

# 🧠 DB-SAVOIR : Maître de la Structure de Données

Ce skill permet à l'agent de comprendre la structure de la base de données, les relations entre les tables et de construire des requêtes SQL correctes.

## 1. Sources de Vérité
Pour comprendre la structure de la base de données, tu DOIS consulter les sources suivantes dans cet ordre de priorité :

1.  **Dossier `database/db_schemas/`** : Contient souvent des dumps ou des schémas JSON/YAML de référence. C'est la source la plus rapide.
2.  **Modèles Eloquent (`modules/*/Models/*.php`)** : Analyse les méthodes de relation (`belongsTo`, `hasMany`, `belongsToMany`) pour comprendre les clés étrangères.
3.  **Migrations (`database/migrations/` et `modules/*/Database/Migrations/`)** : Si les autres sources sont incomplètes, lis les fichiers de migration pour voir la définition exacte des colonnes.
4.  **Base de Données "Live" (Dernier Recours)** : Si tu as accès à un terminal, tu peux utiliser `php artisan model:show {ModelName}` si disponible, ou inspecter via `sqlite3` ou `mysql` en ligne de commande (seulement en lecture seule).

## 2. Capability: "Lire le Chemin de Lecture"
Pour trouver comment aller de la Table A à la Table C (ex: de `Projet` à `Competence`) :

1.  **Identifier les noeuds** : Trouve les modèles `Projet` et `Competence`.
2.  **Chercher les voisins** : Regarde les relations dans `Projet`. Y a-t-il un lien direct ? Si non, trouve une table pivot ou intermédiaire (ex: `Projet` -> `Tache` -> `Competence` ?).
3.  **Tracer le graphe** : Construis le chemin : `$projet->taches->flatMap->competences`.
4.  **Vérifier la cardinalité** :
    - `belongsTo` / `hasOne` -> Accès direct (`$a->b`).
    - `hasMany` / `belongsToMany` -> Collection (`$a->b` renvoie une liste, nécessite une boucle ou un `flatMap`).

## 3. Action : Explorer une Table
Si on demande "Quelle est la structure de la table `users` ?" :
1.  Cherche le fichier `User.php`.
2.  Cherche la migration `create_users_table`.
3.  Synthétise : Liste des colonnes, types, et clés étrangères.

## 4. Action : Trouver une Relation
Si on demande "Comment lier `Apprenant` à `Promotion` ?" :
1.  Vérifie `Apprenant.php` pour une méthode `promotion()` ou `promotions()`.
2.  Si absent, vérifie `Promotion.php` pour `apprenants()`.
3.  Si absent, cherche une table pivot `apprenant_promotion` via les fichiers dans `database/schemas` ou migrations.
