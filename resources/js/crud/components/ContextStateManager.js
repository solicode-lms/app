export class ContextStateManager {
    /**
     * Constructeur pour initialiser ContextStateManager.
     * @param {Object} initialState - L'état initial du contexte.
     * @param {String} prefix - Préfixe pour les paramètres.
     */
    constructor(initialState = {}, prefix = 'context_') {
        this.contextState = initialState;
        this.prefix = prefix; // Préfixe pour les paramètres.
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
        Object.entries(this.contextState).forEach(([key, value]) => {
            prefixedContext[`${this.prefix}${key}`] = value;
        });
        return new URLSearchParams(prefixedContext).toString();
    }

    /**
     * Récupérer le contexte d'état brut.
     * @returns {Object} - L'état brut du contexte.
     */
    getRawContext() {
        return this.contextState;
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
        const prefixedContext = {};
        Object.entries(this.contextState).forEach(([key, value]) => {
            prefixedContext[`${this.prefix}${key}`] = value;
        });
        contextParams = new URLSearchParams(prefixedContext).toString();
    

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
}
