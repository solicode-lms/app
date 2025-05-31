<?php

namespace Modules\Core\Services\Traits;

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

    public function loadLastFilterIfEmpty(){
        
         // TODO : il faut applique seulement les champs filtrable pour que l'utilisateur
        // Il faut l'applique en création de filtre pour initialiser le filtre avec sa 
        // dernière valeur
        // Si vide, essayer de récupérer le filtre enregistré
      
        $filterVariables = $this->viewState->getFilterVariables($this->modelName);
        $this->userHasSentFilter = (count($filterVariables) != 0);
     
        // voir le filtre dans la bar de recherche 
        $userModelFilterService = new UserModelFilterService();
        $isReset = $this->viewState->isResetRequested($this->modelName);
        if ($isReset) {
            // 🔄 Réinitialisation explicite demandée
            $filterVariables = [];
            $userModelFilterService->storeLastFilter($this->modelName, []); // optionnel : reset base
            $this->viewState->removeIsResetRequested($this->modelName);
        }
        elseif (!$this->userHasSentFilter) {
            // 📂 Pas de filtre envoyé = chargement auto
            $saved_filter = $userModelFilterService->getLastSavedFilter($this->modelName) ?? [];
            $filterVariables = array_merge($saved_filter,$filterVariables);
            foreach ($filterVariables as $key => $value) {
                $this->viewState->set("filter.{$this->modelName}.{$key}", $value);
            }
        } else {
            // ✅ Filtre soumis → sauvegarder
            $userModelFilterService->storeLastFilter($this->modelName, $filterVariables);
        }
    }
}
