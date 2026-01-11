# 🎨 Styles de Code et Conventions

## 1. Standards de Développement
- **PSR-12** : Respect strict des standards de codage PHP.
- **SOLID** : Application rigoureuse des principes SOLID.
- **Nommage (Convention Mixte)** :
    - **Français (Langue Client)** : Utilisé pour les **noms de Classes** (Models, Controllers, Services...), les **champs de Base de Données**, et tout code lié au métier (variables et méthodes manipulant des données métier).
    - **Anglais (Technique)** : Utilisé pour le code purement technique, l'infrastructure, et les variables/méthodes qui ne dépendent pas de la base de données (itérateurs, compteurs, helpers génériques, configurations).

## 2. Architecture des Services & Refactoring
- **Héritage** : Tous les services doivent hériter de `BaseService`. Si un service de base spécifique à l'entité existe (ex: `Base[Model]Service`), il doit être utilisé comme parent.
- **Seuil critique (500 Lignes)** : Si une classe Service dépasse 500 lignes, le code doit être découpé en **Traits** situés dans `Services/Traits/{NomEntite}/`.
- **Organisation des Traits** :
    - `{Model}ActionsTrait` : Contient le Workflow, les transitions d'états, les validations métier complexes.
    - `{Model}CalculTrait` : Contient les méthodes `dataCalcul`, les statistiques (`getStats`) et les getters calculés.
    - `{Model}CrudTrait` (Optionnel) : Contient les implémentations des Hooks CRUD si elles sont volumineuses.
- **Classification des Méthodes** : Organiser les méthodes dans cet ordre logique :
    1. **Gestion des Instances et Surcharges CRUD** (`createInstance`, `create`...)
    2. **Hooks de Cycle de Vie** (`before/after` Rules)
    3. **Logique Métier Spécifique** (Actions complexes, workflows)
    4. **Calculs et Enrichissement** (`dataCalcul`, `getStats`)
    5. **Gestion des Relations** (Création de sous-entités)
    6. **Requêtes, Filtres et Scopes** (`defaultSort`)
- **Hooks CRUD** : 
    - Ne jamais surcharger directement les méthodes `create`, `update`, `delete` du `BaseService`.
    - Toujours implémenter les méthodes hooks : `beforeCreate`, `afterCreate`, `beforeUpdate`, `afterUpdate` pour injecter la logique métier.
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
