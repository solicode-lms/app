
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
 *     pour organiser les variables en fonction des modèles (`modelName`).
 */

export class ViewStateService {
    /**
     * Constructeur pour initialiser ViewStateService.
     */
    constructor() {
        this.init();
    }

    init() {
        this.viewState = window.viewState  || { scope: {}, form: {}, table: {}, filter: {}, global: {} };
    }

    getVariablesByType(type, modelName) {
        const allVariables = this.viewState[type] || {};
        const globalVariables = this.viewState[type]?.global || {};
        return { ...globalVariables, ...allVariables[modelName] };
    }

    getScopeVariables(modelName) {
        return this.getVariablesByType('scope', modelName);
    }

    getFormVariables(modelName) {
        return this.getVariablesByType('form', modelName);
    }

    getTableVariables(modelName) {
        return this.getVariablesByType('table', modelName);
    }

    getFilterVariables(modelName) {
        return this.getVariablesByType('filter', modelName);
    }

    addVariable(type, modelName, key, value) {
        if (!this.viewState[type]) {
            this.viewState[type] = {};
        }
        if (!this.viewState[type][modelName]) {
            this.viewState[type][modelName] = {};
        }
        this.viewState[type][modelName][key] = value;
    }

    addData(type, modelName, data) {
        if (!this.viewState[type]) {
            this.viewState[type] = {};
        }
        if (!this.viewState[type][modelName]) {
            this.viewState[type][modelName] = {};
        }
        Object.entries(data).forEach(([key, value]) => {
            this.viewState[type][modelName][key] = value;
        });
    }

    updateContext(newState) {
        this.viewState = { ...this.viewState, ...newState };
    }

    getContextParams() {
        const params = new URLSearchParams();
        Object.entries(this.viewState).forEach(([type, models]) => {
            Object.entries(models).forEach(([modelName, values]) => {
                Object.entries(values).forEach(([key, value]) => {
                    params.append(`${type}.${modelName}.${key}`, value);
                });
            });
        });
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
        return JSON.stringify(this.viewState);
    }
}
