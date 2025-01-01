export class ContexteStateHandler {
    /**
     * Constructeur pour initialiser ContexteStateHandler.
     * @param {Object} contextState - L'objet contenant les variables de contexte.
     * @param {String} mode - Mode d'ajout des paramètres : "prefixed" ou "json".
     * @param {String} targetClass - La classe CSS cible pour appliquer les modifications.
     */
    constructor(contextState = {}, mode = 'prefixed', targetClass = 'context-state') {
        this.contextState = contextState;
        this.mode = mode; // Mode : "prefixed" ou "json".
        this.prefix = 'context_'; // Préfixe pour les paramètres (en mode "prefixed").
        this.targetClass = targetClass; // Classe CSS cible.
    }

       /**
     * Ajoute le contexte à l'objet config, y compris dans les URLs, et le retourne.
     * @param {Object} config - L'objet de configuration à modifier.
     * @returns {Object} - L'objet de configuration avec les paramètres de contexte ajoutés.
     */
       addContextToConfig(config) {
        // Clone l'objet de configuration pour éviter les modifications directes
        const updatedConfig = { ...config };

        // Préparer les paramètres de contexte sous forme de chaîne
        let contextParams;
        if (this.mode === 'json') {
            contextParams = `context=${encodeURIComponent(JSON.stringify(this.contextState))}`;
        } else {
            const prefixedContext = {};
            Object.entries(this.contextState).forEach(([key, value]) => {
                prefixedContext[`${this.prefix}${key}`] = value;
            });
            contextParams = new URLSearchParams(prefixedContext).toString();
        }

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

    
    /**
     * Met à jour le contexte d'état.
     * @param {Object} newState - Les nouvelles variables à ajouter ou remplacer dans le contexte.
     */
    updateContext(newState) {
        this.contextState = { ...this.contextState, ...newState };
    }

    /**
     * Récupérer les paramètres de contexte sous forme adaptée (préfixée ou JSON).
     * @returns {Object|String} - Paramètres avec préfixe ou JSON string.
     */
    getContextParams() {
        if (this.mode === 'json') {
            return JSON.stringify(this.contextState);
        } else if (this.mode === 'prefixed') {
            const prefixedContext = {};
            Object.entries(this.contextState).forEach(([key, value]) => {
                prefixedContext[`${this.prefix}${key}`] = value;
            });
            return new URLSearchParams(prefixedContext).toString();
        }
        return '';
    }

    /**
     * Ajoute les variables du contexte aux liens ayant la classe cible.
     */
    updateLinks() {
        const contextParams = this.getContextParams();

        document.querySelectorAll(`a.${this.targetClass}`).forEach(link => {
            const url = new URL(link.href, window.location.origin);

            if (this.mode === 'json') {
                url.searchParams.set('context', contextParams); // Ajout d'un seul paramètre JSON
            } else {
                contextParams.split('&').forEach(param => {
                    const [key, value] = param.split('=');
                    if (key && value) {
                        url.searchParams.set(key, value);
                    }
                });
            }

            link.href = url.toString();
        });
    }

    /**
     * Ajoute les variables du contexte aux formulaires ayant la classe cible.
     */
    updateForms() {
        const contextParams = this.getContextParams();

        document.querySelectorAll(`form.${this.targetClass}`).forEach(form => {
            if (this.mode === 'json') {
                let input = form.querySelector('input[name="context"]');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'context';
                    form.appendChild(input);
                }
                input.value = contextParams; // Ajouter tout le contexte sous forme JSON
            } else {
                Object.entries(this.contextState).forEach(([key, value]) => {
                    let input = form.querySelector(`input[name="${this.prefix}${key}"]`);
                    if (!input) {
                        input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${this.prefix}${key}`;
                        form.appendChild(input);
                    }
                    input.value = value; // Ajouter le contexte préfixé
                });
            }
        });
    }

    /**
     * Applique dynamiquement le contexte aux nouvelles requêtes AJAX.
     * @param {String} url - L'URL de la requête.
     * @param {Object} options - Les options pour la requête fetch.
     * @returns {Promise<Response>} - La réponse de la requête.
     */
    fetchWithContext(url, options = {}) {
        const contextParams = this.getContextParams();

        if (this.mode === 'json') {
            const separator = url.includes('?') ? '&' : '?';
            const fullUrl = `${url}${separator}context=${contextParams}`;
            return fetch(fullUrl, options)
                .then(response => response.json())
                .catch(error => console.error('Erreur dans la requête AJAX:', error));
        } else {
            const separator = url.includes('?') ? '&' : '?';
            const fullUrl = `${url}${separator}${contextParams}`;
            return fetch(fullUrl, options)
                .then(response => response.json())
                .catch(error => console.error('Erreur dans la requête AJAX:', error));
        }
    }

    /**
     * Initialiser la gestion du contexte d'état pour les liens et formulaires ayant la classe cible.
     */
    init() {
        this.updateLinks();
        this.updateForms();
        this.observeDynamicLinks();
    }


    /**
 * Masque les éléments <select> dont l'id correspond à une clé dans le contextState.
 */
hideSelectsById() {
    Object.keys(this.contextState).forEach((key) => {
        const selectElement = document.getElementById(key);
        if (selectElement && selectElement.tagName === 'SELECT') {
            selectElement.style.display = 'none';
        }
    });
}
}
