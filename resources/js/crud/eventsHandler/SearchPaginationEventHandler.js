import { NotificationHandler } from '../components/NotificationHandler';

export class SearchPaginationEventHandler {
    /**
     * Constructeur de SearchPaginationEventHandler.
     * @param {Object} config - Configuration contenant les sélecteurs et URLs.
     * @param {Object} entityLoader - Instance de LoadListAction pour recharger les entités.
     */
    constructor(config, entityLoader) {
        this.config = config;
        this.entityLoader = entityLoader;

        // Temps de délai pour la recherche (debounce)
        this.debounceTimeout = null;
        this.debounceDelay = 500; // Par défaut : 500ms
    }

    /**
     * Initialise les gestionnaires pour la recherche et la pagination.
     */
    init() {
        this.handleSearchInput();
        this.handleFilterSubmit();
        this.handlePaginationClick();
    }

    /**
     * Gère les événements de recherche avec un délai pour limiter les requêtes fréquentes.
     */
    handleSearchInput() {
        $(document).on('keyup', this.config.searchInputSelector, (e) => {
            const searchValue = $(e.currentTarget).val();
            this.updateURLParameter('q', searchValue);
            clearTimeout(this.debounceTimeout); // Réinitialiser le délai précédent
            this.debounceTimeout = setTimeout(() => {
                this.entityLoader.loadEntities(1, searchValue); // Recharger les entités avec la valeur de recherche
                NotificationHandler.showInfo('Recherche en cours...');
            }, this.debounceDelay);
        });
    }

    /**
     * Gère les clics sur les liens de pagination.
     */
    handlePaginationClick() {
        $(document).on('click', this.config.paginationSelector, (e) => {
            e.preventDefault();
            const page = $(e.currentTarget).data('page') || $(e.currentTarget).attr('data-page') || $(e.target).text().trim();

            // const page = $(e.currentTarget).data('page') || $(e.target).text().trim();

            if (page) {
                const searchValue = $(this.config.searchInputSelector).val(); // Obtenir la valeur actuelle de la recherche
                this.updateURLParameter('page', page); // Met à jour l'URL
                this.entityLoader.loadEntities(page, searchValue); // Charger la page demandée
                NotificationHandler.showInfo(`Chargement de la page ${page}...`);
            }
        });
    }

    /**
     * Met à jour un paramètre dans l'URL sans recharger la page.
     * @param {string} param - Nom du paramètre à mettre à jour.
     * @param {string|number} value - Valeur du paramètre.
     */
    updateURLParameter(param, value) {
        const url = new URL(window.location.href);

        if (value === undefined || value === null || value === '') {
            url.searchParams.delete(param); // Supprime le paramètre s'il n'y a pas de valeur
        } else {
            url.searchParams.set(param, value); // Met à jour ou ajoute le paramètre
        }

        // Met à jour l'URL dans la barre d'adresse sans recharger la page
        window.history.replaceState({}, '', url);
    }
}
