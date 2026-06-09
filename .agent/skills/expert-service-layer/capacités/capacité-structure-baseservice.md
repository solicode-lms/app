# Capacité : Structure de BaseService et Méthodes Utiles

## 🎯 Rôle
Fournir une cartographie exhaustive de la classe de base `BaseService` (`Modules\Core\Services\BaseService`) et de ses traits associés, afin que l'agent et le développeur sachent quelles méthodes appeler ou surcharger sans réinventer la logique existante.

---

## 🏗️ Composition de `BaseService`
`BaseService` implémente `ServiceInterface` et utilise un ensemble de traits spécialisés localisés dans `Modules\Core\Services\Traits\` :
- **CRUD & Écriture** : `CrudTrait`, `CrudCreateTrait`, `CrudUpdateTrait`, `CrudDeleteTrait`, `CrudEditTrait`
- **Lecture & Extraction** : `CrudReadTrait`, `PaginateTrait`
- **Recherche & Filtrage** : `QueryBuilderTrait`, `FilterTrait`, `SortTrait`, `OrdreTraite`
- **Relations** : `RelationTrait`
- **Calculs & Utilitaires** : `StatsTrait`, `MessageTrait`, `JobTrait`, `HandleThrowableTrait`

---

## 📂 Classification des Méthodes Disponibles

### 1. Opérations de Lecture & Récupération (`CrudReadTrait`)
- **`all(array $columns = ['*'])`** : Retourne tous les enregistrements en appliquant `defaultSort()`.
  - *Exemple* : `$this->projetService->all();`
- **`find(int $id, array $columns = ['*'])`** : Récupère une instance par son identifiant unique.
  - *Exemple* : `$this->projetService->find($id);`
- **`getByReference(string $reference, array $columns = ['*'])`** : Récupère une entité par sa référence unique.
  - *Exemple* : `$this->projetService->getByReference($ref);`
- **`getByIds(array $ids, array $columns = ['*'])`** : Récupère une collection d'entités par un tableau d'IDs.
  - *Exemple* : `$this->projetService->getByIds([1, 2, 3]);`
- **`count()`** : Compte le nombre d'enregistrements du modèle.
  - *Exemple* : `$this->projetService->count();`
- **`getAllForSelect($entity)`** : Retourne la collection brute pour alimenter les listes déroulantes (select).
  - *Exemple* : `$this->projetService->getAllForSelect($projet);`

---

### 2. Opérations d'Écriture & Cycle de Vie CRUD
Ces opérations s'exécutent au sein d'une transaction de base de données et déclenchent des hooks spécifiques dans les traits CRUD associés (ex: `beforeCreateRules`).
- **`create(array|object $data)`** (provenant de `CrudCreateTrait`) : Valide, insère l'entité et synchronise ses relations ManyToMany. Déclenche `beforeCreateRules` et `afterCreateRules`.
- **`createInstance(array $data = [])`** (provenant de `CrudCreateTrait`) : Crée une instance en mémoire en fusionnant les variables du formulaire ViewState sans la persister en base.
- **`update($idOrItem, array $data)`** (provenant de `CrudUpdateTrait`) : Met à jour l'entité en base. Déclenche `beforeUpdateRules` et `afterUpdateRules`.
- **`updateOnlyExistanteAttribute($idOrItem, array $data)`** (provenant de `CrudUpdateTrait`) : Met à jour uniquement les attributs présents dans le tableau `$data`.
- **`updateOrCreate(array $attributes, array $values)`** (provenant de `CrudCreateTrait`) : Cherche l'entité par `$attributes`, la met à jour si elle existe, sinon la crée avec `$values`.
- **`destroy($id)`** (provenant de `CrudDeleteTrait`) : Supprime l'entité de la base de données. Déclenche `beforeDeleteRules` et `afterDeleteRules`.

---

### 3. Construction de Requêtes & Filtrage (`QueryBuilderTrait`, `FilterTrait`, `SortTrait`)
- **`newQuery()`** : Construit la requête Eloquent de base. Gère la substitution dynamique via `$dataSources`.
  - *Exemple* : `$query = $this->newQuery();`
- **`allQuery(array $params = [], $query = null)`** : Crée la requête complète en appliquant la recherche globale, les filtres ViewState (AND / OR) et le tri.
  - *Exemple* : `$query = $this->allQuery($params);`
- **`filter($builder, $model, $filters)`** : Applique des filtres strictes (AND) basés sur les colonnes fillable ou relations imbriquées.
  - *Exemple* : `$this->filter($query, $model, $filters);`
- **`paginate(array $params = [], int $perPage = 0)`** : Pagine les résultats de la requête en chargeant les relations définies dans `$index_with_relations`.
  - *Exemple* : `$this->projetService->paginate($params);`

---

### 4. Gestion des Relations & Traitement en Cascade (`RelationTrait`)
- **`syncManyToManyRelations($entity, $data)`** : Synchronise automatiquement les liaisons ManyToMany configurées dans le modèle `$entity->manyToMany`.
  - *Exemple* : Appel automatique lors de `create`/`update`.
- **`getNestedRelationAsCollection($model, $relation, $id)`** : Parcourt et extrait sous forme de collection plate une relation imbriquée (ex: `'modules.competences'`).
  - *Exemple* : `$comp = $this->getNestedRelationAsCollection(Filiere::class, 'modules.competences', $id);`

---

### 5. Sécurité, Rôles & Habilitations (`BaseService.php`)
- **`authorize(string $ability, mixed $entity)`** : Lève une exception `AuthorizationException` si l'utilisateur ne possède pas le droit requis.
- **`getFieldsEditable()`** : Récupère la liste des champs modifiables selon le rôle de l'utilisateur connecté en croisant `editableFieldsByRoles()`.
- **`sanitizePayloadByRoles(array $payload, $model, $user)`** : Nettoie les données soumises en préservant les valeurs d'origine pour les champs non autorisés par le rôle.

---

### 6. Ordonnancement & Tri Dynamique (`OrdreTraite.php`)
- **`reorderOrdreColumn($ancien, $nouveau, $id, $groupValue)`** : Décale l'ordre des autres entités (du même groupe s'il y a un `ordreGroupColumn`) lorsqu'un élément change d'ordre ou est inséré.

---

### 7. Notification & Messages de Session (`MessageTrait`)
- **`pushServiceMessage(string $type, string $title, string $message)`** : Injecte un message flash en session (`service_messages`) affiché automatiquement dans le thème UI (ex: SweetAlert2).
  - *Exemple* : `$this->pushServiceMessage('success', 'Succès', 'Action réussie !');`

---

## ⚡ Propriétés Clés à Surcharger dans le Service Enfant

Pour personnaliser le comportement d'un service hérité, vous pouvez configurer les propriétés suivantes dans votre classe finale `[Model]Service.php` :
- `protected array $index_with_relations = []` : Liste des relations à charger en Eager Loading (évite le problème des requêtes N+1 sur la page index).
- `protected $paginationLimit = 20` : Limite de pagination par défaut.
- `protected $ordreGroupColumn = null` : Nom de la clé étrangère utilisée pour grouper le tri par ordre (ex: `'projet_id'` pour regrouper les tâches par projet).
- `protected $dataSources = []` : Permet de définir des sources de requêtes spécifiques alternatives utilisables par l'interface.
