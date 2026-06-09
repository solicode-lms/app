---
name: expert-gapp-metadata
description: Expert pour déterminer et générer la configuration JSON des métadonnées Gapp (scopeDataInEditContext, scopeDataByRole, ownedByUser).
---

# Skill : Expert Gapp Metadata

## 🎯 Périmètre Global
**Mission** : Aider le développeur à déterminer la structure des relations entre les objets de la base de données et à générer la configuration JSON exacte pour les métadonnées de l'application Gapp.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Validation Métier** : Ne jamais inventer des relations ou des chemins d'attributs. **OBLIGATION** d'utiliser le skill [db-savoir](/skills/db-savoir/SKILL.md) pour trouver le chemin des relations et comprendre la structure exacte de la base de données.
2. **Langue** : Toutes les explications et la documentation générées pour le développeur doivent être en **Français**.
3. **Format Strict** : Fournir uniquement du code JSON valide pour la configuration des métadonnées afin d'éviter tout dysfonctionnement du générateur Gapp.

---

## ⚡ Actions (Orchestration)

### Action A : Configurer scopeDataInEditContext
> **Description** : Déterminer et générer le JSON pour filtrer (scoper) les options d'un select ManyToOne ou ManyToMany dans un sous-formulaire d'édition (contexte hasMany ou hasOne).
- **Capacités Utilisées** :
  - `capacités/capacite-scope-data-in-edit-context.md`
- **Entrées** :
  - `Objet cible` (le modèle du formulaire d'édition principal)
  - `Relation hasMany` (la relation contenant le sous-formulaire)
  - `Champ à scoper` (la clé étrangère/dropdown à filtrer)
  - `Source de filtrage` (le champ parent ou attribut contenant la valeur de filtre)
- **Sorties** :
  - Afficher le Bloc JSON au développeur
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacite-scope-data-in-edit-context` pour analyser les relations des modèles impliqués.
  2. Générer le JSON final conforme.

### Action B : Configurer scopeDataByRole
> **Description** : Déterminer et générer le JSON pour filtrer les options d'un select en fonction du rôle de l'utilisateur connecté.
- **Capacités Utilisées** :
  - `capacités/capacite-scope-data-by-role.md`
- **Entrées** :
  - `Objet/Model` (contenant le champ à scoper)
  - `Champ à scoper` (dropdown concerné)
  - `Rôle` (ex: formateur, apprenant)
  - `Attribut utilisateur` (clé de liaison ex: formateur_id)
- **Sorties** :
  - Bloc JSON à insérer dans le fichier de métadonnées de l'attribut concerné.
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacite-scope-data-by-role` pour tracer la relation vers l'entité liée au rôle de l'utilisateur.
  2. Fournir la configuration JSON finale.

### Action C : Configurer scopeDataByConnectedUser (ownedByUser)
> **Description** : Déterminer et générer le JSON pour filtrer la portée d'un modèle entier en fonction de l'utilisateur connecté (métadonnée `ownedByUser` avec dataScope `scope` ou `filter`).
- **Capacités Utilisées** :
  - `capacités/capacite-scope-data-by-connected-user.md`
- **Entrées** :
  - `Objet/Model` (modèle à filtrer globalement ou à scoper dans le formulaire)
  - `Rôle` (ex: formateur, apprenant)
  - `Chemin vers User` (relations reliant le modèle à l'utilisateur)
- **Sorties** :
  - Bloc JSON à insérer dans les métadonnées de modèle pour `ownedByUser`.
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacite-scope-data-by-connected-user` pour identifier le chemin complet de relation vers le modèle `User`.
  2. Déterminer si la portée est de type `filter` (filtrage global de requêtes) ou `scope` (scoping de formulaire).
  3. Générer le JSON structuré correspondant.

---

## 🛠️ Capacités (Savoir-Faire Technique)

### 1. `capacite-scope-data-in-edit-context.md`
- **Rôle** : Tracé et génération de la configuration pour la restriction de listes déroulantes de sous-formulaires.
- **Règles Clés** : S'assurer que le chemin traverse correctement les relations Eloquent de Laravel et correspond à la structure de la base de données.

### 2. `capacite-scope-data-by-role.md`
- **Rôle** : Liaison de champs de formulaire aux rôles pour filtrer dynamiquement les options de sélection.
- **Règles Clés** : Utiliser la clé de session correcte associée au rôle (ex: `formateur_id` pour le rôle `formateur`).

### 3. `capacite-scope-data-by-connected-user.md`
- **Rôle** : Application de la métadonnée `ownedByUser` pour restreindre l'accès à un modèle ou à ses formulaires.
- **Règles Clés** : Le `ownerRelationPath` doit être spécifié à partir du modèle cible vers la relation `user` (en PascalCase pour les relations dans la chaîne).

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Configuration unitaire d'une métadonnée
1. **Collecte du contexte** : Demander ou identifier le formulaire d'édition/création et l'objet à éditer.
2. **Identification de la métadonnée** : Déterminer quelle action (A, B ou C) correspond au besoin.
3. **Analyse des relations** : Exécuter l'étape 1 de la capacité sélectionnée (déterminer le chemin des données).
4. **Génération** : Exécuter l'étape 2 (génération finale de la configuration JSON).
5. **Rapport** : Présenter la configuration avec les instructions d'intégration dans Gapp.
