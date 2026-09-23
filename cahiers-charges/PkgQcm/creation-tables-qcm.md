# Plan de Création et Migration des Tables - PkgQcm

Ce document détaille l'ordre de création chronologique des tables et de leurs relations extraites du diagramme UML `14.PkgQcm.mmd`. 

> **Important :** Chaque table est créée avec ses relations (clés étrangères) associées, en s'assurant que les tables de destination (locales ou externes au package) existent déjà au moment de l'exécution de la migration.

## Ordre Chronologique de Création

### 1. Table `etat_realisation_qcms`
*Dépend de la table externe `sys_colors`.*
- **Champs standards** : `reference` (string, unique), `titre` (string), `description` (string), `is_editable_by_formateur` (boolean)
- **Relations (Foreign Keys)** :
  - `sys_color_id` (ManyToOne vers la table externe `sys_colors`)

### 2. Table `qcms`
*Dépend de la table externe `formateurs`.*
- **Champs standards** : `reference` (string, unique), `titre` (string), `description` (text), `duree_minutes` (integer), `is_duree_limitee` (boolean), `is_publie` (boolean)
- **Relations (Foreign Keys)** :
  - `formateur_id` (ManyToOne vers la table externe `formateurs`)

### 3. Table `question_libs`
*Dépend de la table externe `unite_apprentissages`.*
- **Champs standards** : `reference` (string, unique), `enonce` (text), `type` (string), `explication` (text), `is_actif` (boolean)
- **Relations (Foreign Keys)** :
  - `unite_apprentissage_id` (ManyToOne vers la table externe `unite_apprentissages`)

### 4. Table `proposition_reponses`
*Dépend de la table `question_libs` (déjà créée).*
- **Champs standards** : `reference` (string, unique), `libelle` (text), `is_correcte` (boolean), `ordre` (integer)
- **Relations (Foreign Keys)** :
  - `question_lib_id` (ManyToOne vers `question_libs`)

### 5. Table `question_qcms`
*Dépend des tables `qcms` et `question_libs` (déjà créées).*
- **Champs standards** : `reference` (string, unique), `ordre` (integer), `bareme` (float)
- **Relations (Foreign Keys)** :
  - `qcm_id` (ManyToOne vers `qcms`)
  - `question_lib_id` (ManyToOne vers `question_libs`)

### 6. Table `affectation_qcm_projets`
*Dépend de la table `qcms` et de la table externe `affectation_projets`.*
- **Champs standards** : `reference` (string, unique)
- **Relations (Foreign Keys)** :
  - `affectation_projet_id` (ManyToOne vers la table externe `affectation_projets`)
  - `qcm_id` (ManyToOne vers `qcms`)

### 7. Table `realisation_qcms`
*Dépend des tables créées précédemment et de la table externe `apprenants`.*
- **Champs standards** : `reference` (string, unique), `date_debut` (datetime), `date_fin` (datetime), `date_soumission` (datetime), `statut` (string), `date_validation` (datetime)
- **Relations (Foreign Keys)** :
  - `affectation_qcm_projet_id` (ManyToOne vers `affectation_qcm_projets`)
  - `etat_realisation_qcm_id` (ManyToOne vers `etat_realisation_qcms`)
  - `qcm_id` (ManyToOne vers `qcms`)
  - `apprenant_id` (ManyToOne vers la table externe `apprenants`)

### 8. Table `reponse_qcms`
*Dépend des tables `realisation_qcms` et `question_qcms` (déjà créées).*
- **Champs standards** : `reference` (string, unique), `date_reponse` (datetime)
- **Relations (Foreign Keys)** :
  - `realisation_qcm_id` (ManyToOne vers `realisation_qcms`)
  - `question_qcm_id` (ManyToOne vers `question_qcms`)

### 9. Table pivot `proposition_reponse_reponse_qcm`
*Table associative entre `proposition_reponses` et `reponse_qcms`.*
- **Relations (Foreign Keys)** :
  - `proposition_reponse_id` (ManyToMany vers `proposition_reponses`)
  - `reponse_qcm_id` (ManyToMany vers `reponse_qcms`)

### 10. Table pivot `realisation_ua_projet_reponse_qcm`
*Table associative entre la table externe `realisation_ua_projets` et `reponse_qcms`.*
- **Relations (Foreign Keys)** :
  - `realisation_ua_projet_id` (ManyToMany vers la table externe `realisation_ua_projets`)
  - `reponse_qcm_id` (ManyToMany vers `reponse_qcms`)

---

## Mise à jour de Gapp (Indispensable)

Une fois toutes les tables créées, il est **indispensable** d'inscrire chaque nouvelle table principale du module dans le fichier `db_schemas/modules.json` sous son package respectif (`PkgQcm`). Ce fichier est vital pour le bon fonctionnement du générateur Gapp et la reconnaissance des tables.

---
*Ce document sert de cahier des charges (plan de vol chronologique) pour lancer le skill `expert-create-table`.*
