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

    public function __construct(string $currentContextKey)
    {
        $this->currentContextKey = $currentContextKey;
        $this->viewStateData[$currentContextKey] = [];
    }

    public function setContextKey(string $currentContextKey){
        $this->currentContextKey = $currentContextKey;
    }
    public function setContextKeyIfEmpty(string $currentContextKey){
        if($this->currentContextKey == "default_context"){
            $this->currentContextKey = $currentContextKey;
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

    public function set(string $key, mixed $value): void
    {
        $this->viewStateData[$this->currentContextKey][$key] = $value;
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

    public function getScopeVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope','filter']);
    }

    public function getFormVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope','filter', 'form']);
    }

    public function getTableVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope', 'table']);
    }

    public function getFilterVariables(string $modelName): array
    {
        return $this->extractVariables($modelName, ['scope', 'filter']);
    }

    public function generateTitleFromVariables(): string
    {
        $parts = [];
        foreach ($this->getCurrentContextData() as $key => $value) {
            $parts[] = ucfirst($key) . ': ' . $value;
        }
        return implode(' | ', $parts);
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
}