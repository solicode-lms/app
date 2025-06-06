<?php

namespace Modules\Core\Services;

/**
 * `ViewStateService` gère l'état des vues CRUD dans l'application.
 * Il permet de conserver et transmettre dynamiquement les variables de contexte (`contextKey`)
 * entre le frontend et le backend, facilitant ainsi la gestion des requêtes AJAX et des relations (`hasMany`).
 * 
 * ## Règles de gestion du ViewState :
 * 1. **ContextKey Unique** : le `ViewState` est une structure `array of array`, une page `Gapp Page` contient plusieurs `contextKey`.
 * 2. **Persistance Temporaire** : la persistance sera dans `Gapp Page` via le navigateur, le serveur ne stocke pas le `ViewState`. 
 *    - À chaque requête HTTP, `Gapp Page` transmet un objet `viewState[]` contenant les valeurs du `ViewState` et le `contextKey`.
 *    - Le `VariablesStateServiceProvider` envoie la valeur du `ViewState` dans Blade pour la transmettre à `Gapp Page`.
 * 3. **Génération Dynamique** : `Gapp Page` peut générer plusieurs `contextKey` selon les actions de l'utilisateur.
 * 4. **Mise à Jour et Synchronisation** : Une requête HTTP peut ajouter un nouveau `contextKey`, qui sera ensuite transmis à `Gapp Page`.
 * 5. **Gestion des Conflits** :
 *    - Toutes les requêtes dans une page `Gapp UI` partagent les mêmes valeurs du `ViewState[ContextKey]`.
 *    - Si deux requêtes sont envoyées simultanément, la dernière détermine la valeur finale du `ViewState[ContextKey]`.
 * 6. **Exécution en Contexte** :
 *    - Le code d'une requête est exécuté dans un `currentContextKey`.
 *    - Si plusieurs `contextKey` existent, il faut changer `currentContextKey` à chaque manipulation des données d'un contexte.
 * 
 * - SetViewStateMiddleware : 
 * - VariablesStateServiceProvider : ajouter variable $viewState dans blade
 */
class ViewStateService
{
    protected array $viewStateData = [];
    protected ?string $title = null;
    protected string $currentContextKey;

    // Indique si nous somme en première création de view state pour appliquer les paramètre d'initialisation
    protected bool $isInit = false;

    public function __construct(string $currentContextKey = "default_context")
    {
        $this->currentContextKey = $currentContextKey;
        $this->viewStateData[$currentContextKey] = [];
    }

    public function setContextKey(string $currentContextKey){
        $this->currentContextKey = $currentContextKey;
    }
    public function getContextKey(){
        return $this->currentContextKey;
    }
    public function setContextKeyIfEmpty(string $currentContextKey){
        if($this->currentContextKey == "default_context"){
            $this->currentContextKey = $currentContextKey;
            $this->viewStateData[$currentContextKey] = $this->viewStateData["default_context"];
            $this->isInit = true;
        }
       
    }

    public function getViewKey(): string
    {
        return $this->currentContextKey;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->viewStateData[$this->currentContextKey][$key] ?? $default;
    }

    public function set(string $key, mixed $value, ?string $contextKey = null): void
    {
        $context = $contextKey ?? $this->currentContextKey;

        if (!isset($this->viewStateData[$context])) {
            $this->viewStateData[$context] = [];
        }

        $this->viewStateData[$context][$key] = $value;
    }
    
    public function setIfEmpty(string $key, mixed $value): void
    {
        if($this->get($key) == null){
            $this->viewStateData[$this->currentContextKey][$key] = $value;
        }
       
    }
    public function init(string $key, mixed $value): void
    {
        if($this->isInit){
            $this->viewStateData[$this->currentContextKey][$key] = $value;
        }
       
    }

    public function has(string $key): bool
    {
        return isset($this->viewStateData[$this->currentContextKey][$key]);
    }

    public function remove(string $key): void
    {
        unset($this->viewStateData[$this->currentContextKey][$key]);
    }

    public function clear(): void
    {
        $this->viewStateData[$this->currentContextKey] = [];
    }

    public function getCurrentContextData(): array
    {
        return $this->viewStateData[$this->currentContextKey] ?? [];
    }
    public function getViewStateData(): array
    {
        return $this->viewStateData ?? [];
    }

    public function getDataSourceVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['dataSource']);
    }

    public function getScopeVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope']);
    }

    public function getFormVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope', 'form']);
    }

    public function getTableVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope', 'table']);
    }

    public function getFilterVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['filter']);
    }
    public function getWhereVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['where']);
    }
    public function getOrWhereVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['orWhere']);
    }
    public function getSortVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['sort']);
    }
    public function generateTitleFromVariables(): string
    {
        $parts = [];
        foreach ($this->getCurrentContextData() as $key => $value) {
            $parts[] = ucfirst($key) . ': ' . $value;
        }
        return implode(' | ', $parts);
    }

    public function getScopeVariablesTitles($modelName, $model): string
    {
        $parts = [];
        foreach ($this->getScopeVariables($modelName) as $key => $value) {
            $title = $this->resolveTitle($key, $value, $model);
            $parts[] = ucfirst($key) . ': ' . $title;
        }
        return implode(' | ', $parts);
    }
    
    /**
     * Résoudre le titre en fonction de l'ID et de la relation.
     */
    private function resolveTitle($key, $id, $model): string
    {
        
        // Vérifier si la clé correspond à une relation ManyToOne
        if (isset($model->relations) && array_key_exists($key, $model->relations)) {
            $relationModelClass = $model->relations[$key];
            if (class_exists($relationModelClass)) {
                $relatedModel = $relationModelClass::find($id);
                if ($relatedModel) {
                    return $relatedModel->name ?? $relatedModel->title ?? (string) $id;
                }
            }
        }
    
        // Si aucune relation trouvée, retourner l'ID
        return (string) $id;
    }


    private function extractVariables(string $modelName, array $types): array
    {
        $filteredVariables = [];
        
        foreach ($this->getCurrentContextData() as $key => $value) {
            foreach ($types as $type) {
                if (str_starts_with($key, "$type.$modelName.")) {
                    $filteredKey = str_replace("$type.$modelName.", '', $key);
                    $filteredVariables[$filteredKey] = $value;
                }
                if (str_starts_with($key, "$type.global.")) {
                    $filteredKey = str_replace("$type.global.", '', $key);
                    $filteredVariables[$filteredKey] = $value;
                }
            }
        }
        return $filteredVariables;
    }

    // TODO : il faut lire depuis ViewState au début d'initialisation de viewState
    // Par middellware
    public function userHasSentFilters(string $modelName): bool
    {
        return collect(request()->all())
            ->keys()
            ->filter(fn($k) => str_starts_with($k, "filter.{$modelName}.") || str_starts_with($k, "filter.global."))
            ->isNotEmpty();
    }
    public function isResetRequested(string $modelName): bool
    {
        return $this->get("param.{$modelName}.reset_filter", false);
    }
    public function removeIsResetRequested(string $modelName)
    {
        $this->remove("param.{$modelName}.reset_filter");
    }
}