export class ContexteStateHandler {
    /**
     * Constructeur pour initialiser ContexteStateHandler.
     * @param {Object} contextState - L'objet contenant les variables de contexte.
     * @param {String} mode - Mode d'ajout des paramètres : "prefixed" ou "json".
     * @param {String} targetClass - La classe CSS cible pour appliquer les modifications.
     */
    constructor(contextState = {}, mode = 'prefixed', targetClass = 'contextState') {
        this.contextState = contextState;
        this.mode = mode; // Mode : "prefixed" ou "json".
        this.prefix = 'context_'; // Préfixe pour les paramètres (en mode "prefixed").
        this.targetClass = targetClass; // Classe CSS cible.
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
     * Applique dynamiquement le contexte aux nouveaux liens ajoutés au DOM ayant la classe cible.
     */
    observeDynamicLinks() {
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.addedNodes) {
                    mutation.addedNodes.forEach(node => {
                        if (node.tagName === 'A' && node.classList.contains(this.targetClass)) {
                            const contextParams = this.getContextParams();
                            const url = new URL(node.href, window.location.origin);

                            if (this.mode === 'json') {
                                url.searchParams.set('context', contextParams);
                            } else {
                                contextParams.split('&').forEach(param => {
                                    const [key, value] = param.split('=');
                                    if (key && value) {
                                        url.searchParams.set(key, value);
                                    }
                                });
                            }

                            node.href = url.toString();
                        }
                    });
                }
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
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
}
