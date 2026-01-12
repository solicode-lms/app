# 🎨 Styles de Code et Conventions

## 1. Standards de Développement
- **PSR-12** : Respect strict des standards de codage PHP.
- **SOLID** : Application rigoureuse des principes SOLID.
- **Principe de Responsabilité Unique (SRP) dans les Services** :
    - Un Service A ne doit pas effectuer les calculs ou la logique métier interne d'une Entité B.
    - Il doit déléguer cette tâche au Service B via ses méthodes publiques (ex: `ServiceB::create` ou `ServiceB::beforeCreateRules`).
    - *Exemple* : `ProjetService` ne calcule pas les critères d'une `MobilisationUa`, il appelle `MobilisationUaService`.
- **Nommage (Convention Mixte)** :
    - **Français (Langue Client)** : Utilisé pour les **noms de Classes** (Models, Controllers, Services...), les **champs de Base de Données**, et tout code lié au métier (variables et méthodes manipulant des données métier).
    - **Anglais (Technique)** : Utilisé pour le code purement technique, l'infrastructure, et les variables/méthodes qui ne dépendent pas de la base de données (itérateurs, compteurs, helpers génériques, configurations).

## 2. Architecture des Services & Refactoring
- **Héritage** : Tous les services doivent hériter de `BaseService`. Si un service de base spécifique à l'entité existe (ex: `Base[Model]Service`), il doit être utilisé comme parent.
- **Seuil critique (500 Lignes)** : Si une classe Service dépasse 500 lignes, le code doit être découpé en **Traits** situés dans `Services/Traits/{NomEntite}/`.
- **Organisation des Traits (Convention Standard)** :
    - `{Model}CrudTrait` : Implémentation des Hooks CRUD (`createInstance`, `before/after Rules`).
    - `{Model}ActionsTrait` : Workflow métier, transitions d'états et actions complexes.
    - `{Model}GetterTrait` : Logic de récupération (`get...`, `getCurrent...`) et scopes complexes.
    - `{Model}CalculTrait` : Méthodes de calcul (`dataCalcul`), statistiques et formatage.
    - `{Model}JobTrait` : Gestion des Jobs asynchrones (`ObserverJob`).
    - `{Model}MassCrudTrait` : Opérations de masse, initialisation par lot, ou import/export en volume.
- **Classification des Méthodes** : Organiser les méthodes dans cet ordre logique :
    1. **Gestion des Instances et Surcharges CRUD** (`createInstance`, `create`...)
    2. **Hooks de Cycle de Vie** (`before/after` Rules)
    3. **Logique Métier Spécifique** (Actions complexes, workflows)
    4. **Calculs et Enrichissement** (`dataCalcul`, `getStats`)
    5. **Gestion des Relations** (Création de sous-entités)
    6. **Requêtes, Filtres et Scopes** (`defaultSort`)
- **Hooks CRUD (Signatures et Usage)** :
    - Ne jamais surcharger directement les méthodes `create`, `update`, `delete` du `BaseService` sauf cas exceptionnel.
    - **`beforeCreateRules(array &$data)`** :
        - **Passage par référence obligatoire** (`&`) pour pouvoir modifier les données avant insertion.
        - Utiliser pour : Validation métier, calcul de champs par défaut, enrichissement de données.
    - **`afterCreateRules($item)`** :
        - Reçoit l'objet créé.
        - Utiliser pour : Création d'enfants, notifications, jobs asynchrones, synchronisations.
    - **`beforeUpdateRules($item, array $data)`** :
        - Reçoit l'item actuel et les nouvelles données.
        - Utiliser pour : Validation de transition d'état, règles de modification.
    - **`afterUpdateRules($item, array $data)`** :
        - Utiliser pour : Mises à jour en cascade, logs d'audit.
    - **`beforeDeleteRules($item)`** :
        - Utiliser pour : Vérifier les dépendances bloquantes (règles de gestion de suppression).
- **Règle** : Ne pas mettre de logique métier lourde dans les Contrôleurs. Déléguer aux Services.

## 3. Format de Réponse
- **Bloc de code** : Bien formaté, prêt à l'emploi.
- **Explication** : Ligne par ligne ou fonctionnelle.
- **Conseils** : Ajouter des recommandations pertinentes.

## 4. Intégration AdminLTE
- Utiliser les composants visuels AdminLTE v3.
- Tableaux responsives, filtres dynamiques (Select2).
- Affichage conditionnel selon rôles.

## 5. Documentation
- Documenter chaque méthode complexe (PHPDoc).
- Expliquer les choix d'architecture dans les réponses.

## 6. Diagrammes de Séquence (Mermaid)
- **Format** : Utiliser Mermaid (`.mmd`).
- **Emplacement** : `docs/1.conception/{Module}/`.
- **Contenu** : Modéliser les interactions complexes entre Services, notamment les Hooks (`afterCreateRules`).
- **Convention** :
    - `participant Service` pour la logique métier.
    - `participant Model` uniquement pour les opérations BDD pures.
    - Utiliser `note` pour expliquer le "Pourquoi".
