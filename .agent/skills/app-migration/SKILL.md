---
name: app-migration
description: Expert en création de tables de base de données via migrations
---

# Skill : Expert Créateur de Tables

## 🎯 Périmètre Global
**Mission** : Assister le développeur dans la création de nouvelles tables et relations en générant les fichiers de migration et en mettant à jour la configuration Gapp.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Pas d'Exécution de Migration** : Ne JAMAIS exécuter la commande de migration (`php artisan migrate`). Il faut toujours demander à l'utilisateur de le faire.
2. **Identification du Package Obligatoire** : Ne pas générer de migration sans avoir identifié ou demandé explicitement le package de destination.
3. **Création Table par Table** : Lors d'une génération depuis un plan (Action C), NE JAMAIS générer plusieurs migrations d'un coup. Traiter **une seule table à la fois**, puis **STOPPER et attendre l'accord explicite du développeur** avant de passer à la suivante.

---

## ⚡ Actions (Orchestration)

### Action A : Générer Migration pour Nouvelle Table
> **Description** : Analyse la demande, propose la commande de génération de migration, génère le code de migration selon les standards du projet, met à jour `modules.json` et guide le développeur pour la suite.
- **Capacités Utilisées** :
  - `capacités/capacité-regles-table.md`
  - `capacités/capacité-generation-migration.md`
- **Entrées** : `Nom de la table`, `Champs/Relations demandés`
- **Sorties** : `Code de la migration`, `Mise à jour de db_schemas/modules.json`
- **❌ Interdictions Spécifiques** :
  - Ne pas exécuter la commande de migration (`php artisan migrate`).
- **✅ Points de Contrôle** :
  - Chaque table principale doit avoir un champ `reference` (`$table->string('reference')->unique();`).
  - L'ajout dans `modules.json` est correctement formaté.
- **📝 Instructions d'Orchestration** :
  1. **Vérifier l'existence du Module** : Avant toute chose, vérifier si le dossier `modules/[NomPackage]/` existe. Si le module n'existe pas encore, indiquer au développeur d'exécuter la commande Gapp de création du module : `gapp make:module "[NomPackage]"` (ex: `gapp make:module "PkgQcm"`) et **STOPPER** en attendant la confirmation que c'est fait, avant de continuer.
  2. **Déterminer le Package** : Analyser le contexte pour trouver le package de la table. Si introuvable, poser la question au développeur et arrêter l'exécution.
  3. **Commande de création** : Indiquer au développeur la commande à exécuter : `php artisan make:module-migration create_[nom_table]_table [NomPackage]`.
  4. **Générer le Code** : Utiliser la `capacité-generation-migration` pour fournir le code complet de la migration (avec la gestion de `up()` et `down()`).
  4. **Mise à jour Gapp** : Insérer les noms des tables créées dans le fichier `db_schemas/modules.json` pour inscrire la table au générateur Gapp.
  5. **Instructions de Suite** : Expliquer au développeur d'exécuter la migration (`php artisan migrate`), puis l'inviter à exécuter les commandes de création des interfaces CRUD par Gapp (`gapp meta:sync` puis `gapp make:crud [NomModel]` pour la nouvelle table **AINSI QUE pour tous les modèles en relation**, car ils sont impactés par les changements). Ensuite, lui demander d'exécuter le seeder généré pour ajouter les droits d'accès (`php artisan db:seed --class=Modules\[NomPackage]\Database\Seeders\[NomModel]Seeder`). Ensuite, indiquer au développeur qu'il doit modifier le fichier de traduction (`modules\[NomPackage]\resources\lang\fr\[nomModel].php`), et rappeler que l'administrateur doit configurer les droits d'accès depuis l'interface d'administration. **Enfin, si la table implique des relations ManyToOne ou ManyToMany nécessitant un filtrage dynamique en cascade, demander au développeur d'ajouter la configuration `scopeDataInEditContext` directement dans la partie administration de Gapp (App Web), et LUI FOURNIR le bout de code JSON exact à copier-coller (ex: `[{"key": "scope.nomModeleFiltre.champ_id", "value": "relationCourante.champ_id", "modelName": "NomDuModelCourant"}]`).**

### Action B : Générer Migration pour Relation entre Deux Tables
> **Description** : Analyse la demande, propose la commande de génération de migration pour ajouter une relation ManyToOne ou ManyToMany entre deux tables, génère le code de migration selon les standards du projet et guide le développeur pour la suite.
- **Capacités Utilisées** :
  - `capacités/capacité-migration-relation.md`
- **Entrées** : `Table source`, `Table cible`, `Type de relation (ManyToOne, ManyToMany)`, `Nom de la relation / colonne`
- **Sorties** : `Code de la migration pour la relation`
- **❌ Interdictions Spécifiques** :
  - Ne pas exécuter la commande de migration (`php artisan migrate`).
- **✅ Points de Contrôle** :
  - Les contraintes de clés étrangères sont correctement écrites avec les options de cascade adaptées.
  - La méthode `down()` supprime proprement les clés étrangères et les colonnes créées.
- **📝 Instructions d'Orchestration** :
  1. **Déterminer le Package** : Analyser le contexte pour trouver le package de la table source. Si introuvable, poser la question au développeur.
  2. **Commande de création** : Indiquer au développeur la commande à exécuter : `php artisan make:module-migration add_[nom_relation]_to_[nom_table_source]_table [NomPackage]`.
  3. **Générer le Code** : Utiliser la `capacité-migration-relation` pour fournir le code complet de la migration.
  4. **Instructions de Suite** : Expliquer au développeur d'exécuter la migration (`php artisan migrate`), puis de synchroniser et régénérer les CRUD avec Gapp (`php artisan gapp meta:sync` puis `php artisan gapp make:crud [NomModel]`).

### Action C : Analyser et Générer depuis un diagramme UML
> **Description** : Lit un fichier Mermaid pour en extraire la structure de la base de données, détermine l'ordre chronologique de création, et pilote itérativement les Actions A et B pour générer les migrations complètes.
- **Capacités Utilisées** :
  - `capacités/capacité-analyse-uml.md`
- **Entrées** : `Chemin du fichier .mmd`
- **Sorties** : `Plan de création` et `Génération des migrations (via Actions A et B)`
- **✅ Points de Contrôle** :
  - L'ordre chronologique de création est strictement respecté.
  - S'assurer que chaque nouvelle table est inscrite dans `db_schemas/modules.json` sous son package respectif (indispensable pour Gapp).
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-analyse-uml` pour extraire les entités et définir l'ordre chronologique de création.
  2. Afficher un résumé du plan de toutes les tables et relations trouvées pour validation par le développeur.
  3. Une fois le plan validé, **traiter UNE SEULE table à la fois** :
     a. Appeler l'Action A pour générer le code de migration de la table en cours (avec ses clés étrangères).
     b. **STOPPER et demander au développeur** : *"La migration pour `[nom_table]` est prête. Veuillez exécuter `php artisan migrate`, puis confirmez pour passer à la table suivante : `[nom_table_suivante]`."*
     c. Attendre la confirmation explicite avant de passer à l'étape suivante.
  4. Pour les tables pivots (ManyToMany), appliquer le même protocole via l'Action B.
  5. Finaliser avec le rappel impératif de mettre à jour `db_schemas/modules.json` pour toutes les nouvelles tables.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-regles-table.md`
- **Rôle** : Définition des règles métiers, standards structurels (champs obligatoires comme reference) et conventions de nommage pour toute table (principale ou pivot) du projet.
- **Règles Clés** : Clés primaires, références uniques, traçabilité, standards Gapp.

### 2. `capacité-generation-migration.md`
- **Rôle** : Standards de syntaxe PHP/Laravel pour la création des fichiers de migration.
- **Règles Clés** : Syntaxe des clés étrangères et tables pivots, déroulement de up() et down().

### 3. `capacité-migration-relation.md`
- **Rôle** : Standards de syntaxe pour l'ajout de relations (clés étrangères ou pivot) entre tables existantes.
- **Règles Clés** : Utilisation correcte de Schema::table(), suppression des contraintes foreign dans down(), synchronisation Gapp.

### 4. `capacité-analyse-uml.md`
- **Rôle** : Standards de lecture et d'analyse des diagrammes UML (Mermaid).
- **Règles Clés** : Ordre chronologique de création, conventions de nommage Laravel, cas spécifiques des relations implicites.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario : Action A
1. Réception de la demande de la structure de table.
2. Si le module/package n'est pas clair, demander au développeur et s'arrêter.
3. Fournir la commande : `php artisan make:module-migration create_x_table PkgY`.
4. Fournir le code de la migration.
5. Modifier `db_schemas/modules.json` pour y inclure la table.
6. Dire au développeur de lancer la migration, puis `gapp make:crud` pour la table et ses relations, d'exécuter le seeder des permissions (`php artisan db:seed --class=...`), de modifier le fichier de traduction, de configurer les droits via l'interface d'administration, et d'ajouter la configuration `scopeDataInEditContext` (pour le filtrage ManyToOne) depuis l'interface admin Gapp.

### Scénario : Action B
1. Réception de la demande d'ajout de relation.
2. Si le module/package n'est pas clair, demander au développeur.
3. Fournir la commande : `php artisan make:module-migration add_x_to_y_table PkgZ`.
4. Fournir le code de la migration en se basant sur `capacité-migration-relation.md`.
5. Expliquer au développeur comment exécuter la migration, synchroniser Gapp et régénérer les CRUD.

### Scénario : Action C (Génération depuis UML)
1. L'utilisateur invoque l'expert avec un fichier UML (ex: `14.PkgQcm.mmd`).
2. Appliquer le protocole de `capacité-analyse-uml` pour parser le document.
3. Présenter le plan de création chronologique complet (liste de toutes les tables dans l'ordre) pour validation.
4. **Une fois validé, traiter UNE table à la fois** :
   - Générer la commande et le code de migration pour la table courante (Action A ou B).
   - Indiquer : *"✅ Migration pour `[nom_table]` générée. Próchaine étape : `[nom_table_suivante]`. Confirmez après avoir exécuté `php artisan migrate`."*
   - **STOPPER. Attendre la confirmation avant de continuer.**
5. Répéter l'étape 4 pour chaque table du plan.
6. Finaliser avec le rappel impératif de mettre à jour `modules.json`.

