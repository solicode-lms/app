---
name: expert-view-state
description: Expertise dans la manipulation, le filtrage et la configuration du ViewState dans Gapp.
---

# Skill : Expert ViewState

## 🎯 Périmètre Global
**Mission** : Accompagner les développeurs dans l'utilisation et la manipulation du `ViewState` (Gapp) au sein des Controllers et des Services, notamment pour le filtrage dynamique et les appels AJAX.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Scopes Dynamiques** : Ne jamais configurer de filtrage AJAX dynamique (ex: `getData`) sans utiliser les variables `scope.` ou `where.`.
2. **allQuery vs withScope** : Toujours se rappeler que `allQuery()` n'applique pas automatiquement les `scopeVariables`. Celles-ci requièrent d'être encapsulées dans `withScope()`.

---

## ⚡ Actions (Orchestration)

### Action A : Configurer un Filtre ViewState (Controller/Service)
> **Description** : Ajouter ou modifier un paramètre de filtrage via ViewState pour un module spécifique.
- **Capacités Utilisées** :
  - `capacites/capacite-manipulation-viewstate.md`
- **Entrées** : `Modèle cible`, `Type de filtre (where/scope)`, `Valeur de filtre`
- **Sorties** : Code injecté dans le Service ou le Controller.
- **❌ Interdictions Spécifiques** :
  - Ne pas insérer de requêtes manuelles complexes (ex: `whereHas`) dans le `Service` si le ViewState peut gérer le relationnel (ex: `scope.module.relation.field`).
- **✅ Points de Contrôle** :
  - La méthode visée (ex: `initFieldsFilterable` ou `index`) inclut la bonne clé `viewState->set()`.
- **📝 Instructions d'Orchestration** :
  1. **Analyse** : Déterminer si le besoin nécessite un `where.` (traitement normal) ou un `scope.` (filtrage contextuel strict AJAX).
  2. **Injection** : Ajouter le code `$this->viewState->set(...)` au bon endroit.
  3. **Vérification `getData`** : S'assurer que le service traitant l'appel AJAX (ex: `getData`) est bien enveloppé dans `withScope()` si des `scopeVariables` sont utilisés.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacites/`*

### 1. `capacite-manipulation-viewstate.md`
- **Rôle** : Base de connaissances sur le fonctionnement du ViewState dans Solicode LMS.
- **Règles Clés** : Utilisation de `whereVariables` vs `scopeVariables`, gestion des relations imbriquées (dot syntax), et utilisation du `DynamicContextScope`.

---

## 🔄 Scénarios d'Exécution (Algorithmes)
### Scénario 1 : Intervention Unitaire
1. Identifier si le filtre concerne un appel statique ou un appel AJAX (`getData`).
2. Appliquer l'Action A selon le contexte.
