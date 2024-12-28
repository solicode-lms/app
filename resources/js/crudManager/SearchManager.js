export class SearchManager {
    /**
     * Constructeur de la classe SearchManager.
     * @param {Object} config - Configuration de la recherche.
     * @param {string} config.inputSelector - Sélecteur CSS pour le champ de recherche.
     * @param {string} config.resultContainerSelector - Sélecteur CSS pour le conteneur des résultats.
     * @param {Function} fetchData - Fonction pour récupérer les données basées sur la valeur de recherche.
     * @param {number} [debounceTime=300] - Temps de délai pour la recherche (en ms).
     */
    constructor(config, fetchData, debounceTime = 300) {
        this.inputSelector = config.inputSelector;
        this.resultContainerSelector = config.resultContainerSelector;
        this.fetchData = fetchData;
        this.debounceTime = debounceTime;
        this.debounceTimeout = null;
        this.init();
    }

    /**
     * Initialise les gestionnaires d'événements pour le champ de recherche.
     */
    init() {
        $(document).on('keyup', this.inputSelector, () => {
            this.handleInputChange();
        });
    }

    /**
     * Gère les changements dans le champ de recherche avec un délai (debounce).
     */
    handleInputChange() {
        const searchValue = $(this.inputSelector).val().trim();

        clearTimeout(this.debounceTimeout); // Réinitialise le délai précédent

        this.debounceTimeout = setTimeout(() => {
            this.fetchData(searchValue);
        }, this.debounceTime);
    }

    /**
     * Met à jour dynamiquement le conteneur des résultats.
     * @param {string} html - Contenu HTML des résultats à injecter.
     */
    updateResults(html) {
        $(this.resultContainerSelector).html(html);
    }

    /**
     * Affiche un message d'erreur ou aucun résultat.
     * @param {string} message - Message d'erreur ou d'absence de résultats.
     */
    showError(message) {
        $(this.resultContainerSelector).html(`<div class="alert alert-warning">${message}</div>`);
    }
}
