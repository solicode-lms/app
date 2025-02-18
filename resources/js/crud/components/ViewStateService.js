
/**
 * `ViewStateService` gère l'état des vues dans Gapp UI en permettant 
 * la persistance temporaire des contextes de gestion CRUD. 
 * Il facilite la gestion des relations (`hasMany`), l'optimisation des requêtes AJAX 
 * et assure la transmission dynamique des variables de contexte (`contextKey`) 
 * entre le frontend et le backend.
 *
 * Règles de gestion :
 * - **ContextKey Unique** : `ViewState` est une structure `array of array`, une page Gapp UI 
 *   peut contenir plusieurs `contextKey`.
 * - **Persistance Temporaire** : Les données sont stockées dans le navigateur et non sur le serveur.
 *   À chaque requête HTTP, Gapp UI transmet `viewState[]` avec `contextKey` au serveur, 
 *   qui renvoie les valeurs mises à jour.
 * - **Gestion Dynamique des Contextes** :
 *   - Gapp UI peut générer plusieurs `contextKey`.
 *   - Une requête HTTP peut ajouter un nouveau `contextKey`, qui sera transmis à Gapp UI.
 *   - Les données de `viewState[contextKey]` sont partagées entre toutes les requêtes HTTP dans une page.
 *   - Si plusieurs requêtes utilisent le même `contextKey`, la dernière mise à jour prévaut.
 * - **Mise à Jour des Données** : 
 *   - À chaque requête, Gapp UI met à jour `viewState` avec les valeurs provenant du serveur.
 *   - Un `contextKey` peut être réutilisé pour synchroniser les vues `editHasMany`, `create`, `edit`, `index`.
 * - **Gestion des Conflits** :
 *   - Si plusieurs contextes sont actifs, Gapp UI doit définir `currentContextKey` avant toute interaction.
 *   - Une gestion explicite est requise pour éviter les collisions entre différentes requêtes simultanées.
 * - **Transmission des Données** :
 *   - Toute requête AJAX envoie le `contextKey` en paramètre.
 *   - `viewState` contient des sous-catégories (`scope`, `form`, `table`, `filter`) 
 *     pour organiser les variables en fonction des modèles (`this.modelName`).
 */

export class ViewStateService {
    static viewState = window.viewState || {}; // Stocke tous les viewStates par contextKey

    constructor(contextKey, modelName) {
        this.contextKey = contextKey;
        this.modelName = modelName;
        ViewStateService.init();

        if (!ViewStateService.viewState[this.contextKey]) {
            ViewStateService.viewState[this.contextKey] = {};
        }
    }

    /**
     * Initialise le ViewState en mettant à jour les contextes existants
     * et en insérant les nouveaux contextes ajoutés par contextKey.
     */
    static init() {

        const newViewState = window.viewState || {};
        Object.entries(newViewState).forEach(([contextKey, contextData]) => {

            ViewStateService.viewState[contextKey] = contextData;

            // if (!ViewStateService.viewState[contextKey]) {
              
            // } else {
            //     Object.assign(ViewStateService.viewState[contextKey], contextData);
            // }
        });
    }

    getContext() {
        return ViewStateService.viewState[this.contextKey];
    }

    getVariable(key) {
        return this.getContext()[key] ?? null;
    }

    setVariable(key, value) {
        this.getContext()[key] = value;
    }

    removeVariable(key) {
        delete this.getContext()[key];
    }

    /**
     * Récupérer les variables par type et modèle.
     * @param {Array|string} types - Liste des types (ex: ['scope', 'form'])
     * @param {string} this.modelName - Nom du modèle concerné (ex: 'projet')
     * @returns {Object} Variables filtrées
     */
    getVariablesByType(types) {
        if (!Array.isArray(types)) {
            types = [types];
        }
        
        return Object.entries(this.getContext())
            .filter(([key]) => 
                types.some(type => key.startsWith(`${type}.${this.modelName}.`)) || 
                types.some(type => key.startsWith(`${type}.global.`))
            )
            .reduce((acc, [key, value]) => {
                types.forEach(type => {
                    if (key.startsWith(`${type}.${this.modelName}.`)) {
                        acc[key.replace(`${type}.${this.modelName}.`, '')] = value;
                    } else if (key.startsWith(`${type}.global.`)) {
                        acc[key.replace(`${type}.global.`, '')] = value;
                    }
                });
                return acc;
            }, {});
    }

    getScopeVariables() {
        return this.getVariablesByType(['scope']);
    }
    getScopeFormVariables() {
        return this.getVariablesByType(['scope','scope_form']);
    }
    
    getFormVariables() {
        return this.getVariablesByType(['scope','scope_form', 'form','filter']);
    }

    getTableVariables() {
        return this.getVariablesByType(['scope', 'table']);
    }

    getFilterVariables() {
        return this.getVariablesByType(['scope', 'filter']);
    }

    updatFilterVariables(filterData) {
        if (!ViewStateService.viewState[this.contextKey]) {
            ViewStateService.viewState[this.contextKey] = {};
        }
    
        Object.entries(filterData).forEach(([key, value]) => {
            const filterKey = `filter.${this.modelName}.${key}`;
    
            if (value === "" || value === null || value === undefined) {
                delete ViewStateService.viewState[this.contextKey][filterKey];
            } else {
                ViewStateService.viewState[this.contextKey][filterKey] = value;
            }
        });
    }

    updatSortVariables(sortData) {
        if (!ViewStateService.viewState[this.contextKey]) {
            ViewStateService.viewState[this.contextKey] = {};
        }
    
        Object.entries(sortData).forEach(([key, value]) => {
            const filterKey = `sort.${this.modelName}.${key}`;
    
            if (value === "" || value === null || value === undefined) {
                delete ViewStateService.viewState[this.contextKey][filterKey];
            } else {
                ViewStateService.viewState[this.contextKey][filterKey] = value;
            }
        });
    }
    

    updateContext(newState) {
        Object.assign(this.getContext(), newState);
    }

    getContextParams() {
        const params = new URLSearchParams();
        const contextData = { 
            viewState: this.getContext(), 
            contextKey: this.contextKey 
        };
        params.append('viewState', JSON.stringify(contextData));
        return params.toString();
    }

    addContextToConfig(config) {
        const updatedConfig = { ...config };
        const contextParams = this.getContextParams();

        Object.keys(updatedConfig).forEach((key) => {
            if (key.toLowerCase().endsWith('url') && typeof updatedConfig[key] === 'string') {
                const url = new URL(updatedConfig[key], window.location.origin);
                const separator = url.search ? '&' : '?';
                updatedConfig[key] = `${url.toString()}${separator}${contextParams}`;
            }
        });
        return updatedConfig;
    }

    toString() {
        return JSON.stringify(ViewStateService.viewState);
    }
}
