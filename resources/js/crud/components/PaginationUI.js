import EventUtil from './../utils/EventUtil';

export class PaginationUI {
    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI; // Référence à `TableUI`
        this.page = 1;
    }

    /**
     * Initialise la gestion des événements de pagination.
     */
    init() {
        this.handlePaginationClick(); // Gérer les clics de pagination
    }

    /**
     * Gère les clics sur les liens de pagination.
     */
    handlePaginationClick() {
        EventUtil.bindEvent('click', this.config.paginationSelector, (e) => {
            e.preventDefault();
    
            const page = $(e.currentTarget).data('page') || $(e.target).text().trim();
    
            if (page) {
                const filters = this.indexUI.filterUI.getFormData(true); // Inclure tous les champs, même vides
                filters.page = page; // Ajouter le numéro de page
                this.page = page;
    
                // Mettre à jour l'URL avec tous les paramètres
                this.indexUI.updateURLParameters(filters);
    
                // Charger les entités avec les filtres et la page
                this.indexUI.tableUI.entityLoader.loadEntities(page, filters);
            }
        });
    }

}
