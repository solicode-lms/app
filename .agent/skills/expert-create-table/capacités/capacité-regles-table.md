# Capacité : Règles des Tables

## 🎯 Rôle
Définir les standards métiers et les règles architecturales absolues que doit respecter toute table de base de données dans le projet Solicode LMS.

## 📝 Standards des Tables Principales (Entités Fortes)
Toute table représentant une entité principale du domaine (ex: `qcms`, `projets`, `taches`) doit **obligatoirement** respecter ces règles :

1. **Clé Primaire** : Un champ `id` auto-incrémenté.
2. **Référence Unique** : Un champ `reference` de type `string` avec une contrainte `unique`. Ce champ sert d'identifiant fonctionnel et pour les imports/exports.
3. **Traçabilité** : Les champs temporels de création et modification (gérés via `timestamps`).
4. **Nommage** : 
   - Le nom de la table doit être en **`snake_case` au pluriel** (ex: `etat_realisation_qcms`).
   - Le nom des clés étrangères pointant vers la table doit être le singulier du nom de la table suivi de `_id` (ex: `etat_realisation_qcm_id`).

## 🔗 Standards des Tables Pivots (Relations Many-To-Many)
1. **Identifiant** : Une table pivot ne doit **jamais** posséder de clé primaire `id`.
2. **Traçabilité** : Les `timestamps` sont nécessaires.
3. **Nommage** :
   - Les noms des clés étrangères doivent correspondre exactement au modèle cible (ex: si le modèle est `LabelProjet`, la clé doit être `label_projet_id`).
   - Le nom de la table pivot est la concaténation au singulier et par ordre alphabétique (sauf règle métier spécifique) des entités liées.

## 🗂️ Appartenance Modulaire (Packages)
Chaque table appartient obligatoirement à un Package métier (ex: `PkgQcm`, `PkgFormation`, `Core`).
1. **db_schemas/modules.json** : Toute nouvelle table (y compris pivot) doit impérativement être déclarée dans ce fichier, sous le bloc de son package. C'est indispensable pour que le générateur Gapp puisse la manipuler.
