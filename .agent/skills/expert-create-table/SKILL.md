---
name: expert-create-table
description: Expert en création de tables de base de données via migrations
---

# Skill : Expert Créateur de Tables

## 🎯 Périmètre Global
**Mission** : Assister le développeur dans la création de nouvelles tables en générant les fichiers de migration et en mettant à jour la configuration Gapp.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Pas d'Exécution de Migration** : Ne JAMAIS exécuter la commande de migration (`php artisan migrate`). Il faut toujours demander à l'utilisateur de le faire.
2. **Identification du Package Obligatoire** : Ne pas générer de migration sans avoir identifié ou demandé explicitement le package de destination.

---

## ⚡ Actions (Orchestration)

### Action A : Générer Migration pour Nouvelle Table
> **Description** : Analyse la demande, propose la commande de génération de migration, génère le code de migration selon les standards du projet, met à jour `modules.json` et guide le développeur pour la suite.
- **Capacités Utilisées** :
  - `capacités/capacité-generation-migration.md`
- **Entrées** : `Nom de la table`, `Champs/Relations demandés`
- **Sorties** : `Code de la migration`, `Mise à jour de db_schemas/modules.json`
- **❌ Interdictions Spécifiques** :
  - Ne pas exécuter la commande de migration (`php artisan migrate`).
- **✅ Points de Contrôle** :
  - Chaque table principale doit avoir un champ `reference` (`$table->string('reference')->unique();`).
  - L'ajout dans `modules.json` est correctement formaté.
- **📝 Instructions d'Orchestration** :
  1. **Déterminer le Package** : Analyser le contexte pour trouver le package de la table. Si introuvable, poser la question au développeur et arrêter l'exécution.
  2. **Commande de création** : Indiquer au développeur la commande à exécuter : `php artisan make:module-migration create_[nom_table]_table [NomPackage]`.
  3. **Générer le Code** : Utiliser la `capacité-generation-migration` pour fournir le code complet de la migration (avec la gestion de `up()` et `down()`).
  4. **Mise à jour Gapp** : Insérer les noms des tables créées dans le fichier `db_schemas/modules.json` pour inscrire la table au générateur Gapp.
  5. **Instructions de Suite** : Expliquer au développeur d'exécuter la migration (`php artisan migrate`), puis l'inviter à exécuter les commandes de création des interfaces CRUD par Gapp (`gapp meta:sync` puis `gapp make:crud [NomModel]` pour la nouvelle table **AINSI QUE pour tous les modèles en relation**, car ils sont impactés par les changements). Ensuite, lui demander d'exécuter le seeder généré pour ajouter les droits d'accès (`php artisan db:seed --class=Modules\[NomPackage]\Database\Seeders\[NomModel]Seeder`). Ensuite, indiquer au développeur qu'il doit modifier le fichier de traduction (`modules\[NomPackage]\resources\lang\fr\[nomModel].php`), et rappeler que l'administrateur doit configurer les droits d'accès depuis l'interface d'administration. **Enfin, si la table implique des relations ManyToOne ou ManyToMany nécessitant un filtrage dynamique en cascade, demander au développeur d'ajouter la configuration `scopeDataInEditContext` directement dans la partie administration de Gapp (App Web), et LUI FOURNIR le bout de code JSON exact à copier-coller (ex: `[{"key": "scope.nomModeleFiltre.champ_id", "value": "relationCourante.champ_id", "modelName": "NomDuModelCourant"}]`).**

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-generation-migration.md`
- **Rôle** : Standards de syntaxe pour la création de tables et de relations.
- **Règles Clés** : Présence du champ reference, typage des relations ManyToOne et ManyToMany (sans ID pour les tables pivots).

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario : Action A
1. Réception de la demande de la structure de table.
2. Si le module/package n'est pas clair, demander au développeur et s'arrêter.
3. Fournir la commande : `php artisan make:module-migration create_x_table PkgY`.
4. Fournir le code de la migration.
5. Modifier `db_schemas/modules.json` pour y inclure la table.
6. Dire au développeur de lancer la migration, puis `gapp make:crud` pour la table et ses relations, d'exécuter le seeder des permissions (`php artisan db:seed --class=...`), de modifier le fichier de traduction, de configurer les droits via l'interface d'administration, et d'ajouter la configuration `scopeDataInEditContext` (pour le filtrage ManyToOne) depuis l'interface admin Gapp.
