export class ContextStateService {
    /**
     * Constructeur pour initialiser ContextStateService.
     * @param {Object} initialState - L'état initial du contexte.
     */
    constructor() {
        this.init();
    }

    init(){
        this.contextState = window.contextState;
    }

    getVariables(){
        return this.contextState.variables;
    }
    getVariablesByType(type) {
        const allVariables = this.contextState.variables || {};
        const filteredVariables = {};
        const globalVariables = {};
    
        Object.entries(allVariables).forEach(([key, value]) => {
            if (key.includes(`__${type}__`)) {
                const cleanKey = key.replace(new RegExp(`^.*?__${type}__`), '');
                filteredVariables[cleanKey] = value;
            } else if (!key.includes('__form__') && !key.includes('__filter__') && !key.includes('__table__')) {
                globalVariables[key] = value;
            }
        });
    
        return { ...globalVariables, ...filteredVariables };
    }
    
    getFormVariables() {
        return this.getVariablesByType('form');
    }
    
    getTableVariables() {
        return this.getVariablesByType('table');
    }
    
    getFilterVariables() {
        return this.getVariablesByType('filter');
    }
    
    getGlobalVariables() {
        const allVariables = this.contextState.variables || {};
        const globalVariables = {};
    
        Object.entries(allVariables).forEach(([key, value]) => {
            if (!key.includes('__form__') && !key.includes('__filter__') && !key.includes('__table__')) {
                globalVariables[key] = value;
            }
        });
    
        return globalVariables;
    }
    
    
    

   /**
     * Ajoute une variable au contexte d'état.
     * @param {String} key - La clé de la variable.
     * @param {*} value - La valeur de la variable.
     */
    addVariable(key, value) {
        if (!this.contextState.variables) {
            this.contextState.variables = {};
        }
        this.contextState.variables[key] = value;
    }


    /**
     * Ajoute plusieurs variables au contexte d'état à partir d'un objet.
     * @param {Object} arrayData - Objet contenant les paires clé/valeur.
     */
    addData(data) {
        if (!this.contextState.variables) {
            this.contextState.variables = {};
        }

        Object.entries(data).forEach(([key, value]) => {
            this.contextState.variables[key] = value;
        });
    }

    /**
     * Met à jour le contexte d'état.
     * @param {Object} newState - Les nouvelles variables à ajouter ou remplacer dans le contexte.
     */
    updateContext(newState) {
        this.contextState = { ...this.contextState, ...newState };
    }

    /**
     * Récupérer les paramètres de contexte sous forme préfixée.
     * @returns {String} - Paramètres avec préfixe sous forme de chaîne.
     */
    getContextParams() {
        const prefixedContext = {};
        Object.entries(this.contextState.variables).forEach(([key, value]) => {
            prefixedContext[`${key}`] = value;
        });
        return new URLSearchParams(prefixedContext).toString();
    }


       /**
     * Ajoute le contexte à l'objet config, y compris dans les URLs, et le retourne.
     * @param {Object} config - L'objet de configuration à modifier.
     * @returns {Object} - L'objet de configuration avec les paramètres de contexte ajoutés.
     */
    addContextToConfig(config) {
        // Clone l'objet de configuration pour éviter les modifications directes
        const updatedConfig = config ;

        // Préparer les paramètres de contexte sous forme de chaîne
        let contextParams;
        contextParams = new URLSearchParams(updatedConfig).toString();
    
        // Ajouter les paramètres de contexte aux URLs
        Object.keys(updatedConfig).forEach((key) => {
            if (key.toLowerCase().endsWith('url') && typeof updatedConfig[key] === 'string') {
                const url = new URL(updatedConfig[key], window.location.origin);
                const separator = url.search ? '&' : '?';
                updatedConfig[key] = `${url.toString()}${separator}${contextParams}`;
            }
        });

        return updatedConfig;
    }

    toString(){
        return  JSON.stringify(this.getVariables());
    }
}
