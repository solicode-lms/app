---
name: expert-service-layer
description: "Expertise de l'architecture modulaire de la couche Service, des Traits et des règles de modification (gapp)."
---

# Skill : Expert Couche Service (expert-service-layer)

## 🎯 Périmètre Global
**Mission** : Guider l'agent et le développeur dans la structuration, l'analyse et la modification de la couche Service métier dans le projet Solicode LMS, en respectant rigoureusement l'architecture générée par Gapp, le découpage en Traits et l'héritage objet.

### 🚫 Interdictions Globales (Règles d'Or)
1. **Intégrité de Gapp** : Ne JAMAIS modifier un fichier commençant par `// Ce fichier est maintenu par ESSARRAJ Fouad` (classes de niveau `Base/` telles que `Base[Model]Service.php`).
2. **Architecture MVC vs Service** : Interdiction de placer de la logique métier lourde dans les contrôleurs. Toute la logique métier doit être encapsulée dans la couche Service (classe `[Model]Service` ou ses Traits correspondants).
3. **Modification de BaseService** : Ne jamais modifier directement le fichier `BaseService` du noyau core, sauf sous forme de PR validée pour des améliorations génériques de la stack.

### 📢 Gestion des Messages & Exceptions Métier (BLL)
Dans la couche Service, la remontée d'erreurs ou d'alertes à l'utilisateur ne doit pas se faire par des redirections directes ou des retours HTTP. Elle repose sur le mécanisme suivant :
- **Levée de BlException** : Pour interrompre l'exécution suite à une règle métier ou de validation violée, importez `Modules\Core\App\Exceptions\BlException` et lancez l'exception :
  ```php
  throw new BlException("Message explicatif en Français.");
  ```
- **Affichage Automatique** : Cette exception est capturée par le Handler global et affiche son contenu dans l'UI (SweetAlert2 ou toast).
- **Formatage HTML** : Le message d'erreur accepte des balises HTML simples comme `</br>` ou `<b>` pour soigner la présentation.
- **Messages Flash** : Pour notifier un succès ou une information non bloquante, utilisez le helper :
  ```php
  $this->pushServiceMessage('success|info|warning', 'Titre', 'Description');
  ```

---

## 🏗️ Architecture et Règle d'Héritage

La couche Service repose sur une hiérarchie stricte sur 3 niveaux :
1. **`BaseService` (Niveau Core)** : Classe abstraite fondamentale (`Modules\Core\Services\BaseService`) implémentant le `ServiceInterface`.
2. **`Base[Model]Service` (Niveau Généré)** : Classe intermédiaire générée automatiquement par l'outil `gapp` (ex: `BaseProjetService`). **INTERDICTION STRICTE DE MODIFIER CE FICHIER.**
3. **`[Model]Service` (Niveau Métier)** : Classe de service finale modifiable (ex: `ProjetService`). C'est ici que le code personnalisé (surcharges et nouvelles méthodes) doit être implémenté.

### Découpage en Traits (Composition)
Dans les classes de service finales (`[Model]Service`), le code métier complexe est découpé logiciellement à l'aide de Traits regroupés par catégorie de traitement dans `modules/Pkg[Module]/Services/Traits/[Model]/` :
- `[Model]CrudTrait` : Hook sur le cycle de vie CRUD (`beforeCreateRules`, `afterCreateRules`, etc.).
- `[Model]ActionsTrait` : Actions métiers spécifiques (import, export, workflow).
- `[Model]CalculTrait` : Calculs de statistiques, agrégations et enrichissement.
- `[Model]RelationsTrait` : Synchronisation et mise à jour des relations.

---

## ⚡ Actions (Orchestration)

### Action A : Analyser la couche Service d'une entité
> **Description** : Inspecter la hiérarchie de Service d'un modèle et identifier ses extensions et Traits associés avant toute modification.
- **Capacités Utilisées** :
  - `capacités/capacité-structure-baseservice.md`
- **Entrées** : `Nom du modèle (ex: Projet)`
- **Sorties** : `Rapport d'analyse de la structure du Service (classe principale et Traits existants)`
- **❌ Interdictions Spécifiques** :
  - Ne pas proposer de modifications sans avoir localisé la classe finale `[Model]Service.php` et ses Traits (ex: `[Model]CrudTrait.php`).
- **✅ Points de Contrôle** :
  - Identification claire du dossier du module concerné dans `modules/`.
  - Distinguer la classe `Base[Model]Service` (générée, intouchable) de la classe `[Model]Service` (extensible).
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-structure-baseservice.md` pour identifier les traits et méthodes hérités de `BaseService`.
  2. Localiser le fichier `[Model]Service.php` dans `modules/Pkg[Module]/Services/`.
  3. Identifier les Traits importés par ce service (généralement situés dans `modules/Pkg[Module]/Services/Traits/[Model]/`).
  4. Vérifier les méthodes surchargées et comprendre la hiérarchie en remontant à `Base[Model]Service` puis `BaseService`.

### Action B : Personnaliser le tri par défaut
> **Description** : Surcharger ou définir l'ordre de tri appliqué par défaut à une entité lors des requêtes d'indexation.
- **Capacités Utilisées** :
  - `capacités/capacité-tri-defaut.md`
- **Entrées** : `Service cible`, `Expression du besoin (tri par ordre, date de création, référence, etc.)`
- **Sorties** : `Méthode defaultSort($query) ajoutée ou modifiée dans le service ou son trait associé`
- **❌ Interdictions Spécifiques** :
  - Ne jamais modifier les fichiers dans `Base/`.
- **✅ Points de Contrôle** :
  - Surcharge effectuée dans `[Model]Service` ou un Trait enfant métier.
  - La requête Eloquent retourne le constructeur avec l'ordre désiré.
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-tri-defaut.md` pour implémenter la méthode `defaultSort($query)`.
  2. Si l'entité dispose d'un tri multicritère, chaîner les `orderBy()`.

### Action C : Personnaliser les filtres d'indexation
> **Description** : Ajouter, masquer ou personnaliser les filtres affichés dans la vue d'index d'une entité.
- **Capacités Utilisées** :
  - `capacités/capacité-filtre-custom.md`
- **Entrées** : `Service cible`, `Spécifications du filtre (ManyToOne, Relation, rechargement dynamique)`
- **Sorties** : `Méthode initFieldsFilterable() implémentée ou mise à jour`
- **❌ Interdictions Spécifiques** :
  - Ne jamais appeler `loadLastFilterIfEmpty()` dans un contrôleur ou export sans avoir préalablement initialisé le contexte avec `setContextKeyIfEmpty()` sur le ViewState.
- **✅ Points de Contrôle** :
  - Utilisation des helpers de `FilterTrait` (`generateManyToOneFilter`, etc.).
  - Respect strict des variables de portée (scope variables).
- **📝 Instructions d'Orchestration** :
  1. Ouvrir le service final ou son trait de getters.
  2. Utiliser `capacité-filtre-custom.md` pour structurer la méthode `initFieldsFilterable()`.

### Action D : Surcharger les Hooks CRUD
> **Description** : Gérer la logique métier, la validation ou les effets de bord au cours des opérations de création, modification ou suppression.
- **Capacités Utilisées** :
  - `capacités/capacité-hooks-crud.md`
- **Entrées** : `Service/Trait CRUD cible`, `Hook visé (beforeCreateRules, afterUpdateRules, etc.)`, `Logique métier`
- **Sorties** : `Méthode de hook implémentée ou mise à jour`
- **❌ Interdictions Spécifiques** :
  - Ne pas changer la signature des méthodes héritées ou attendues par `BaseService`.
- **✅ Points de Contrôle** :
  - Les données passées par référence dans `beforeCreateRules` sont correctement manipulées.
  - La validation lève une `BlException` en cas d'échec (voir section *Gestion des Messages*).
- **📝 Instructions d'Orchestration** :
  1. Utiliser `capacité-hooks-crud.md` pour identifier la signature exacte du hook.
  2. Implémenter la logique et lever une `BlException` si une règle est enfreinte.

---

## 🛠️ Capacités (Savoir-Faire Technique)
*Documentation des fichiers situés dans le dossier `capacités/`*

### 1. `capacité-tri-defaut.md`
- **Rôle** : Personnalisation de l'ordre de tri par défaut d'une entité dans les requêtes d'indexation.
- **Règles Clés** : Surcharge de la méthode `defaultSort($query)`, utilisation sécurisée du Query Builder d'Eloquent.

### 2. `capacité-filtre-custom.md`
- **Rôle** : Déclaration et configuration de filtres dynamiques et de rechargements en cascade (Dynamic Dropdowns) dans le Service.
- **Règles Clés** : Surcharge de `initFieldsFilterable()`, utilisation des helpers de `FilterTrait`, respect strict de l'ordre d'appel du contexte ViewState.

### 3. `capacité-hooks-crud.md`
- **Rôle** : Gestion du cycle de vie des opérations CRUD via des hooks pour l'injection, la validation ou les cascades métier.
- **Règles Clés** : Signature exacte des hooks dans le Trait CRUD, passage par référence pour la création, validation métier pré-action.

### 4. `capacité-exceptions-bll.md`
- **Rôle** : Gestion et remontée propre des erreurs de logique métier (Business Logic Layer) à l'utilisateur.
- **Règles Clés** : Levée d'exceptions `BlException`, formatage de messages conviviaux (HTML basique supporté).

### 5. `capacité-structure-baseservice.md`
- **Rôle** : Documentation exhaustive et classification des méthodes et propriétés héritées de la classe abstraite `BaseService` et de ses traits associés.
- **Règles Clés** : Identification et réutilisation des helpers de lecture, d'écriture, de filtrage et de gestion de session.

---

## 🔄 Scénarios d'Exécution (Algorithmes)

### Scénario 1 : Ajout d'une règle métier avec retour utilisateur (BLL)
1. **Analyse** : Déterminer si la règle s'applique à la création, la modification ou la suppression.
2. **Identification** : Ouvrir `[Model]CrudTrait.php` ou `[Model]Service.php` selon la structure existante.
3. **Implémentation** : 
   - Utiliser l'**Action D** pour insérer la logique dans le bon hook (ex: `beforeDeleteRules`).
   - S'appuyer sur la section *Gestion des Messages* pour lever une `BlException` avec le message d'erreur approprié.
4. **Validation** : Présenter la modification et expliquer les conditions de déclenchement de l'erreur.
