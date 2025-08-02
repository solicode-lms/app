<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Support\Facades\DB;
use Modules\Core\Services\UserModelFilterService;

trait FilterTrait
{
    public function getFieldsFilterable(): array
    {
        $this->initFieldsFilterable();
        return $this->fieldsFilterable;
    }

    /**
     * Génère un filtre ManyToOne avec des options formatées.
     *
     * @param string $label Le label du filtre.
     * @param string $field Le nom du champ.
     * @param string $model La classe du modèle.
     * @param string $display_field Le champ affiché dans la liste déroulante.
     * @return array Le filtre formaté.
     */
    protected function generateManyToOneFilter(
        string $label, 
        string $field, 
        string $model, 
        string $display_field,
        $data = null,
        $targetDynamicDropdown = null,
        $targetDynamicDropdownApiUrl = null,
        $targetDynamicDropdownFilter = null
        ): array
    {
        $modelInstance = new $model();
       
        // Appliquer `withScope()` pour activer les scopes si disponibles
        $data = $data ?? $model::withScope(fn() => $model::all());

        return [
            'label' => $label,
            'field' => $field,
            'type' => 'ManyToOne',
            'options' => $data
                ->map(fn($item) => ['id' => $item['id'], 'label' => $item])
                ->toArray(),
            'sortable' => "{$modelInstance->getTable()}.{$display_field}",
            'targetDynamicDropdown' => $targetDynamicDropdown,
            'targetDynamicDropdownApiUrl' => $targetDynamicDropdownApiUrl,
            'targetDynamicDropdownFilter' => $targetDynamicDropdownFilter
        ];
    }

    /**
     * Génère un filtre ManyToMany avec des options formatées.
     *
     * @param string $label Le label du filtre.
     * @param string $field Le nom de la foreignkey ManyToMany : le champs à utiliser pour filter 
     * @param string $relatedModel Le modèle lié dans la relation ManyToMany.
     * @param string $display_field Le champ affiché dans la liste déroulante.
     * @return array Le filtre formaté.
     */
    protected function generateManyToManyFilter(
        string $label, 
        string $field, 
        string $relatedModel, 
        string $display_field,
        $targetDynamicDropdown = null,
        $targetDynamicDropdownApiUrl = null,
        $targetDynamicDropdownFilter = null
        ): array
    {
        $relatedInstance = new $relatedModel();

        return [
            'label' => $label,
            'field' => $field,
            'type' => 'ManyToMany',
            'options' => $relatedModel::all(['id', $display_field])
                ->map(fn($item) => ['id' => $item['id'], 'label' => $item[$display_field]])
                ->toArray(),
            'sortable' => "{$relatedInstance->getTable()}.{$display_field}",
            'targetDynamicDropdown' => $targetDynamicDropdown,
            'targetDynamicDropdownApiUrl' => $targetDynamicDropdownApiUrl,
            'targetDynamicDropdownFilter' => $targetDynamicDropdownFilter
        ];
    }

    /**
     * Génère un filtre Polymorphic avec des options formatées.
     *
     * @param string $label Le label du filtre.
     * @param string $field Le nom du champ.
     * @param string $model La classe du modèle.
     * @param string $display_field Le champ affiché dans la liste déroulante.
     * @return array Le filtre formaté.
     */
    protected function generatePolymorphicFilter(
        string $label, 
        string $field, 
        string $model, 
        string $display_field,
        $targetDynamicDropdown = null,
        $targetDynamicDropdownApiUrl = null,
        $targetDynamicDropdownFilter = null
        ): array
    {
        $modelInstance = new $model();

        return [
            'label' => $label,
            'field' => $field,
            'type' => 'Polymorphic',
            'options' => $model::all(['id', $display_field])
                ->map(fn($item) => ['id' => $item['id'], 'label' => $item[$display_field]])
                ->toArray(),
            'sortable' => "{$modelInstance->getTable()}.{$display_field}",
            'targetDynamicDropdown' => $targetDynamicDropdown,
            'targetDynamicDropdownApiUrl' => $targetDynamicDropdownApiUrl,
            'targetDynamicDropdownFilter' => $targetDynamicDropdownFilter
        ];
    }


    /**
     * Génère un filtre basé sur une relation définie entre les modèles.
     *
     * @param string $label Le label du filtre.
     * @param string $relation La relation définie entre les modèles.
     * @param string $relatedModel Le modèle lié à la relation.
     * @param string|null $displayField Le champ affiché dans la liste déroulante (optionnel, par défaut 'id').
     * @return array Le filtre formaté.
     */
    protected function generateRelationFilter(
        string $label, 
        string $relation, 
        string $relatedModel, 
        string $displayField = 'id', 
        string $valueField = 'id', 
        $data = null,
        $targetDynamicDropdown = null,
        $targetDynamicDropdownApiUrl = null,
        $targetDynamicDropdownFilter = null
        ): array
    {
        $relatedInstance = new $relatedModel();

        // Récupération des données du modèle lié en tenant compte des relations
        $data = $data ?? $relatedModel::withScope(fn() => $relatedModel::all());

          $data =  $data->map(fn($item) => ['id' => $item[$valueField], 'label' => $item])
            ->toArray();

        return [
            'label' => $label,
            'field' => $relation,
            'type' => 'Relation',
            'options' => $data,
            'sortable' => "{$relatedInstance->getTable()}.{$displayField}",
            'targetDynamicDropdown' => $targetDynamicDropdown,
            'targetDynamicDropdownApiUrl' => $targetDynamicDropdownApiUrl,
            'targetDynamicDropdownFilter' => $targetDynamicDropdownFilter
        ];
    }


    /**
     * Initialisation des champs filtrables.
     * Doit être appelée après le choix du contexte (exemple : index).
     */
    public function initFieldsFilterable()
    {
        // À implémenter selon le contexte d'application
    }

    /**
     * TODO : il peut que le filtre contient des information supprimer de la base de 
     * donnée
     * @return void
     */
    public function loadLastFilterIfEmpty(){
        
        $filterVariables = $this->viewState->getFilterVariables($this->modelName);
        $context_key = $this->viewState->getContextKey();
        $this->userHasSentFilter = (count($filterVariables) != 0);
     
        // voir le filtre dans la bar de recherche 
        $userModelFilterService = new UserModelFilterService();
        $isReset = $this->viewState->isResetRequested($this->modelName);
        if ($isReset) {
            // 🔄 Réinitialisation explicite demandée
            $filterVariables = [];
            $userModelFilterService->storeLastFilter($context_key, $this->modelName, []); // optionnel : reset base
            $this->viewState->removeIsResetRequested($context_key, $this->modelName);
        }
        elseif (!$this->userHasSentFilter) {
            // 📂 Pas de filtre envoyé = chargement auto
            $saved_filter = $userModelFilterService->getLastSavedFilter($context_key, $this->modelName) ?? [];
            
            // Il faut vérifier que les données existe encors ans la base de données
            //  $this->checkIfDataExist($saved_filter);
            
            $filterVariables = array_merge($saved_filter,$filterVariables);
            foreach ($filterVariables as $key => $value) {
                $this->viewState->set("filter.{$this->modelName}.{$key}", $value);
            }
        } else {
            // ✅ Filtre soumis → sauvegarder
            $userModelFilterService->storeLastFilter($context_key, $this->modelName, $filterVariables);
        }
    }

    /**
     * Extrait les valeurs DISTINCT d’un champ relationnel (ex: module.filiere.id) via jointures SQL dynamiques.
     *
     * @param string $relationPath  Exemple : "module.filiere.id" ou "module.filiere_id"
     * @param array $params         Les paramètres de filtre (recherche, viewState, etc.)
     * @return array                Liste de valeurs distinctes (ex: [1, 2, 3])
     */
    public function getAvailableFilterValues(string $relationPath): array
    {
        return $this->model->withScope(function () use ($relationPath) {

            // ✅ On utilise `query()` pour créer un builder Eloquent avec les global scopes (comme DynamicContextScope)
            // ✅ Ensuite `.toBase()` permet de convertir ce builder Eloquent en un Query\Builder "pur SQL"
            // 👉 Cela évite de charger automatiquement les relations Eloquent tout en gardant les `join`, `where`, `scope`, etc.
            $query = $this->model->query()->toBase();
            

            // ⛓ Appliquer dynamiquement les jointures SQL selon le chemin relationnel
            //    Exemple : "module.filiere.id" → jointure de module puis filiere
            $column = $this->applyDynamicJoins($query, $relationPath);

            // 🎯 On sélectionne uniquement la colonne ciblée
            $query->select($column);

            // 🧪 Pour debug :
            // dd($query->toSql(), $query->getBindings());
            // dd($query->get()->toArray());

            // 🔁 Extraire les valeurs, les filtrer, les rendre uniques
            return $query
                ->pluck($column)    // ← colonne simple, pas besoin d'alias
                ->filter()
                ->unique()
                ->values()
                ->all();
        });
    }


    protected function sanitizeSelectExpression(string $column): array
{
    // S'assure que le nom d'alias est toujours valide pour Laravel
    $column = trim($column);

    if (str_contains($column, '.')) {
        $alias = 'filter_value';
    } else {
        $alias = $column;
    }

    return [$alias, $column];
}
}
