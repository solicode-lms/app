# Capacité : Gestion des Filtres de Données
**Skill Associé :** `app-blade` (Interface et Vues)

Cette capacité explique comment ajouter et configurer des filtres de recherche sur les listes (index) générées par Gapp.

## 🚫 Règles d'Or
1. **Intégrité JSON de Gapp** : Il est **STRICTEMENT INTERDIT** de modifier soi-même les fichiers JSON de métadonnées Gapp (ex: dans `db_schemas/tables/e_metadata/`). Le skill doit uniquement *générer et fournir la configuration JSON* ; c'est le développeur qui l'ajoutera lui-même dans l'interface d'administration ou manuellement dans le fichier.
2. **Priorisation Gapp** : Ne pas proposer de surcharger le code si le filtre peut être géré par Gapp de manière native via une simple métadonnée (ManyToOne, RelationFilter, etc.).

## 🔄 Méthodes d'Implémentation

### Méthode 1 : Via Métadonnées Gapp (Privilégiée)
À utiliser si le filtre est une simple relation (ManyToOne, relation directe).
1. Rédiger le code de définition de type métadonnée (ex: un `relationFilter` complet avec `path`, `iModelName`, et `relationType`).
2. Fournir ce bloc formaté en JSON à l'utilisateur.
3. Rappeler la commande `php artisan gapp meta:sync` (qui doit être exécutée après l'ajout manuel par l'utilisateur).

### Méthode 2 : Surcharge dans le Service (Filtre Personnalisé)
À utiliser pour les calculs complexes inter-bases ou logique métier spécifique.
1. Ouvrir le fichier de Service de l'entité (jamais le `BaseService`).
2. Redéfinir la méthode `initFieldsFilterable()` (si inexistante, la cloner à partir de `BaseModelService`).
3. Ajouter/Adapter la mécanique de filtre (ex: avec `$this->generateRelationFilter(...)` conditionné par des règles métier).
