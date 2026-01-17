# Règles Spécifiques - Agent Business

> Ce fichier est la mémoire évolutive des règles métiers strictes. Il est structuré par domaines d'application.

## 1. Documentation & Conception (First Priority)
- **[Règle Diagramme First]** (CRITIQUE) : Avant toute écriture de code complexe (Workflow, Algo métier), **VOUS DEVEZ rédiger ou valider le diagramme Mermaid (`.scenario.mmd`) correspondant**. Le code PHP initial doit être une implémentation stricte de ce diagramme.
  > **Note importante** : Une fois implémenté, le code devient la source de vérité. Lors de modifs futures du code, le diagramme doit être mis à jour pour refléter la réalité (voir Règle Sync Diagramme Change).
- **[Règle Usage Diagramme]** : Tout workflow complexe doit être documenté dans `docs/1.scenarios/{Module}/{Entity}/`. Ce dossier doit contenir : `{NomScenario}.scenario.mmd`.
- **[Règle Granularité]** : Chaque **Use Case** distinct (ex: Création, Suppression) doit avoir son propre fichier de scénario (ex: ne pas mélanger Création et Suppression dans le même fichier si la logique diffère).
- **[Règle Précision Diagramme]** : Dans les diagrammes Mermaid, utilisez l'alias du Trait concerné (ex: `TacheRelationsTrait`) comme participant au lieu du Service global. Cela localise précisément le code.
- **[Règle Simplification Diagramme]** : Ne PAS représenter les interactions standards avec le Model (Eloquent) (ex: `create`, `save`, `find`) sauf si crucial pour la logique. Utilisez plutôt une `Note` sur le Service/Trait indiquant "Sauvegarde BDD" pour alléger le diagramme.

## 2. Architecture & Code
- **[Règle SRP Services]** : Responsabilité Unique par Service. Un Service ne gère que son Entité. Pour interagir avec une autre Entité, passer par le Service de cette dernière (Interdiction de Model étranger direct).
- **[Règle Architectures Traits]** : Pour tout Service utilisant des Traits, la PHPDoc de la classe principale DOIT inclure une section "Architecture" listant chaque Trait via `@uses` avec une description courte.
- **[Règle Typage]** : Chaque méthode publique d'un Service doit avoir un Return Type explicitement typé.
- **[Règle Injection]** : Prioriser l'Injection de Dépendance dans les constructeurs.

## 3. Synchronisation & Maintenance
- **[Règle Sync Scénario - Code]** : Les diagrammes Mermaid doivent être référencés dans la PHPDoc de la classe via `@see ...scenario.mmd`.
- **[Règle Sync Diagramme Change]** : Après toute modification de signature (nom, paramètres) ou de flux dans le code, **L'AGENT DOIT DEMANDER AU DÉVELOPPEUR** : "Souhaitez-vous synchroniser le diagramme de scénario ?". Les paramètres affichés dans le diagramme doivent être STRICTEMENT IDENTIQUES à ceux du code.
- **[Règle Modification Traits]** : Lors de la modification d'un Service avec Traits, **LIRE toutes les méthodes des Traits importés** pour éviter d'écraser des hooks (ex: `afterCreateRules`).
- **[Règle Adaptation Code]** : Lors de l'implémentation d'un diagramme, ne pas modifier inutilement les signatures existantes si elles fonctionnent ; adapter le workflow aux hooks et paramètres disponibles.

## 4. Standardisation des Traits (Guide Refactoring)
Lors du découpage d'un Service, utilisez exclusivement ces catégories de Traits :
- **`{Entity}CrudTrait`** : Cycle de vie (create, update, destroy), Hooks (`before/after`), Règles métier de base.
- **`{Entity}RelationsTrait`** : Gestion des relations complexes, Synchronisations inter-services (ex: `syncTacheRealisation`), Gestion des collections filles.
- **`{Entity}GetterTrait`** : Scopes, Filtres complexes, Requêtes de lecture spécifiques (`getValidatedItems`, `filterByContext`).
- **`{Entity}ActionsTrait`** : Actions métier spécifiques ("Verbes" du domaine) hors CRUD standard (ex: `import`, `export`, `calculateStats`, `sendNotification`).
- **`{Entity}DataCalculTrait`** : Implémentation de la méthode `dataCalcul` pour enrichir les données des formulaires (ex: calculs auto, valeurs par défaut contextuelles).
- **`{Entity}StateTrait`** : (Optionnel) Gestion des machines à états, transitions de statut, validations d'étapes.
