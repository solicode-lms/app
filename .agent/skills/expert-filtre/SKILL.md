---
name: expert-filtre
description: Expert de l'ajout et de la configuration des filtres de données (Via Gapp ou Classe métier).
---

# Skill : Expert Filtre

## 🎯 Périmètre Global
**Mission** : Guider et accompagner le développeur dans la mise en place de filtres sur les listes (index) en respectant les pratiques du projet (Générateur Gapp vs Surcharge manuelle de service).

### 🚫 Interdictions Globales (Règles d'Or)
1. **Intégrité JSON de Gapp** : Il est **STRICTEMENT INTERDIT** à l'agent de modifier lui-même les fichiers JSON de métadonnées Gapp (ex: dans `db_schemas/tables/e_metadata/`). L'agent doit uniquement *générer et fournir la configuration JSON* ; c'est le développeur qui l'ajoutera lui-même dans l'interface d'administration ou manuellement dans le fichier.
2. **Priorisation Gapp** : Ne pas proposer de surcharger le code si le filtre peut être géré par Gapp de manière native via une simple métadonnée (ManyToOne, RelationFilter, etc.).

---

## ⚡ Actions (Orchestration)

### Action A : Accompagnement à la Création de Filtre
> **Description** : Déterminer la meilleure approche pour le filtre et guider le développeur.
- **Entrées** : `Modèle cible`, `Filtre désiré`
- **Sorties** : `Questions de clarification` / `Bloc JSON de métadonnée` / `Code PHP de surcharge`
- **❌ Interdictions Spécifiques** :
  - L'agent ne doit jamais présumer de l'approche ; en cas de doute sur la nécessité d'un calcul complexe, il doit poser la question.
- **✅ Points de Contrôle** :
  - Le développeur est consulté sur le besoin (Filtre basé sur des relations Gapp vs Filtre nécessitant du code spécifique).
- **📝 Instructions d'Orchestration** :
  1. Analyser le besoin en filtre exprimé par le développeur.
  2. Si le modèle relationnel suggère que Gapp peut s'en charger directement (relation classique) : Informer le développeur et exécuter l'Action B.
  3. S'il s'agit d'un calcul complexe inter-bases ou d'une logique métier spécifique : Demander au développeur de choisir entre un "Filtre standard Gapp" ou un "Filtre personnalisé métier".
  4. Si "Filtre personnalisé métier" est choisi : Exécuter l'Action C.

### Action B : Générer la Configuration JSON Gapp
> **Description** : Produire l'extrait JSON exact de métadonnée sans éditer le fichier Gapp.
- **Entrées** : `Chemin relationnel`, `Cible`, `Type`
- **Sorties** : `Extrait JSON complet de la métadonnée Gapp`
- **❌ Interdictions Spécifiques** :
  - Interdiction de modifier le fichier de Gapp via un appel d'outil système.
- **📝 Instructions d'Orchestration** :
  1. Rédiger le code de définition de type métadonnée, par exemple un `relationFilter` complet avec `path`, `iModelName`, et `relationType`.
  2. Fournir ce bloc de code formaté en JSON au développeur dans le message de réponse.
  3. Lui indiquer explicitement qu'il doit ajouter cette configuration via l'interface d'administration de Gapp.
  4. Rappeler la commande `php artisan gapp meta:sync` (qui doit être exécutée après l'ajout).

### Action C : Surcharger le Service avec un Filtre Personnalisé
> **Description** : Ajouter un filtre non standard dans la couche de service en redéfinissant `initFieldsFilterable`.
- **Entrées** : `Classe Service cible`
- **Sorties** : `Code PHP intégré dans le [Model]Service` (jamais le BaseService).
- **❌ Interdictions Spécifiques** :
  - Ne jamais éditer les classes commençant par `Base/Base...`.
- **📝 Instructions d'Orchestration** :
  1. Ouvrir le fichier de Service (ex: `Modules/PkgExemple/Services/MonModelService.php`).
  2. Redéfinir la méthode `initFieldsFilterable()` (si inexistante ou la cloner à partir de BaseModelService).
  3. Ajouter ou adapter la mécanique de filtre (ex: avec `$this->generateRelationFilter(...)` conditionné par des données calculées ou des règles de l'utilisateur).

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Création de filtre à l'initiative du développeur
1. **Demande** : Le développeur demande à ajouter un filtre sur l'entité *A* par rapport à *B*.
2. **Analyse** : S'il y a un calcul ou une condition particulière requise pour construire les options, demander au développeur "Souhaitez-vous gérer cela dynamiquement via un filtre personnalisé en code, ou est-ce un filtre relationnel pur qui peut être injecté dans Gapp ?"
3. **Réponse Développeur** :
   - *Via Gapp* : Configurer et afficher le JSON (Action B).
   - *Via Code* : Éditer la classe Service ciblée avec le code approprié (Action C).
