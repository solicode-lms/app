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
}
