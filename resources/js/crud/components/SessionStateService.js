export class SessionStateService {
    /**
     * Constructeur pour initialiser SessionStateService.
     * @param {Object} initialState - L'état initial de la session.
     * @param {String} prefix - Préfixe pour les paramètres.
     */
    constructor(sessionState = {}, prefix = 'session_') {
        this.sessionState = sessionState;
        this.prefix = prefix; // Préfixe pour les paramètres de session.
    }

    /**
     * Récupère toutes les variables de session.
     * @returns {Object} - Les variables de session.
     */
    getVariables() {
        return this.sessionState.session_data;
    }

    getValue(key){
       return  this.sessionState.session_data[key];
    }

    /**
     * Met à jour l'état de la session.
     * @param {Object} newState - Les nouvelles variables à ajouter ou remplacer.
     */
    updateSession(newState) {
        this.sessionState = { ...this.sessionState, ...newState };
    }

    /**
     * Récupère les paramètres de session sous forme préfixée.
     * @returns {String} - Paramètres avec préfixe sous forme de chaîne.
     */
    getSessionParams() {
        const prefixedSession = {};
        Object.entries(this.sessionState.session_data).forEach(([key, value]) => {
            prefixedSession[`${this.prefix}${key}`] = value;
        });
        return new URLSearchParams(prefixedSession).toString();
    }

    /**
     * Ajoute l'état de session à l'objet config, y compris dans les URLs, et le retourne.
     * @param {Object} config - L'objet de configuration à modifier.
     * @returns {Object} - L'objet de configuration avec les paramètres de session ajoutés.
     */
    addSessionToConfig(config) {
        // Clone l'objet de configuration pour éviter les modifications directes
        const updatedConfig = { ...config };

        // Préparer les paramètres de session sous forme de chaîne
        let sessionParams;
        const prefixedSession = {};
        Object.entries(this.sessionState.session_data).forEach(([key, value]) => {
            prefixedSession[`${this.prefix}${key}`] = value;
        });
        sessionParams = new URLSearchParams(prefixedSession).toString();

        // Ajouter les paramètres de session aux URLs
        Object.keys(updatedConfig).forEach((key) => {
            if (key.toLowerCase().endsWith('url') && typeof updatedConfig[key] === 'string') {
                const url = new URL(updatedConfig[key], window.location.origin);
                const separator = url.search ? '&' : '?';
                updatedConfig[key] = `${url.toString()}${separator}${sessionParams}`;
            }
        });

        return updatedConfig;
    }
}
