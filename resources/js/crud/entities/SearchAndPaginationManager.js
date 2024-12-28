import { MessageHandler } from '../components/MessageHandler';

export class SearchAndPaginationManager {
    /**
     * Constructeur de SearchAndPaginationManager.
     * @param {Object} config - Configuration contenant les sélecteurs et URLs.
     * @param {EntityLoader} entityLoader - Instance de EntityLoader pour recharger les entités.
     */
    constructor(config, entityLoader) {
        this.config = config;
        this.entityLoader = entityLoader;

        // Temps de délai pour la recherche (debounce)
        this.debounceTimeout = null;
        this.debounceDelay = 500; // 500ms par défaut
    }

    /**
     * Initialise les gestionnaires pour la recherche et la pagination.
     */
    init() {
        this.handleSearchInput();
        this.handlePaginationClick();
    }


    updateURLParameter(param, value) {
        const url = new URL(window.location.href);
    
        if (value === undefined || value === null || value === '') {
            url.searchParams.delete(param); // Supprime le paramètre s'il n'y a pas de valeur
        } else {
            url.searchParams.set(param, value); // Met à jour ou ajoute le paramètre
        }
    
        // Met à jour l'URL sans recharger la page
        window.history.replaceState({}, '', url);
    }

    

    /**
     * Gère les événements de recherche avec un délai pour éviter les requêtes fréquentes.
     */
    handleSearchInput() {
        $(document).on('keyup', this.config.searchInputSelector, (e) => {
            const searchValue = $(e.currentTarget).val();

            clearTimeout(this.debounceTimeout); // Réinitialiser le délai précédent
            this.debounceTimeout = setTimeout(() => {
                this.entityLoader.loadEntities(1, searchValue); // Recharger les entités avec la valeur de recherche
                MessageHandler.showInfo('Recherche en cours...');
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

            if (page) {
                const searchValue = $(this.config.searchInputSelector).val(); // Obtenir la valeur actuelle de la recherche
                this.entityLoader.loadEntities(page, searchValue); // Charger la page demandée
                MessageHandler.showInfo(`Chargement de la page ${page}...`);
            }
        });
    }
}
