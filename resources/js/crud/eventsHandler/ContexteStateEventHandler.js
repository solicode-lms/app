import { ContextStateService } from "../components/ContextStateService";


export class ContexteStateEventHandler {
    /**
     * Constructeur pour initialiser ContexteStateEventHandler.
     * @param {ContextStateService} stateManager - Instance de ContextStateService.
     * @param {String} targetClass - La classe CSS cible pour appliquer les modifications.
     */
    constructor(config, targetClass = 'context-state') {
        this.config = config;
        this.contextStateService = this.config.contextStateService; // Instance de ContextStateService.
        this.targetClass = targetClass; // Classe CSS cible.
    }

    /**
     * Ajoute les variables du contexte aux liens ayant la classe cible.
     */
    updateLinks() {
        const contextParams = this.contextStateService.getContextParams();

        document.querySelectorAll(`${this.config.crudSelector} a.${this.targetClass}`).forEach(link => {
            const url = new URL(link.href, window.location.origin);

            contextParams.split('&').forEach(param => {
                const [key, value] = param.split('=');
                if (key && value) {
                    url.searchParams.set(key, value);
                }
            });

            link.href = url.toString();
        });
    }


    

    /**
     * Applique dynamiquement le contexte aux nouvelles requêtes AJAX.
     * @param {String} url - L'URL de la requête.
     * @param {Object} options - Les options pour la requête fetch.
     * @returns {Promise<Response>} - La réponse de la requête.
     */
    fetchWithContext(url, options = {}) {
        const contextParams = this.contextStateService.getContextParams();
        const separator = url.includes('?') ? '&' : '?';
        const fullUrl = `${url}${separator}${contextParams}`;
        return fetch(fullUrl, options)
            .then(response => response.json())
            .catch(error => console.error('Erreur dans la requête AJAX:', error));
    }

    /**
     * Initialiser la gestion des événements pour les liens et formulaires ayant la classe cible.
     */
    init() {
        this.updateLinks();
    }
}
