export class ConfigHelper {
    /**
     * Constructeur de la classe ConfigHelper.
     * @param {Object} config - Configuration des opérations CRUD.
     */
    constructor(config) {

        
        this.edit_has_many = config.edit_has_many; // permet d'éditer l'entity avec ses objet has many
        this.crudSelector = config.crudSelector;
        this.tableSelector = config.tableSelector;
        this.formSelector = config.formSelector;
        this.modalSelector = config.modalSelector;
        this.filterFormSelector = config.filterFormSelector;

        this.indexUrl = config.indexUrl; // URL pour la liste des entités
        this.createUrl = config.createUrl; // URL pour créer une nouvelle entité
        this.showUrl = config.showUrl; // URL pour afficher une entité
        this.editUrl = config.editUrl; // URL pour modifier une entité
        this.storeUrl = config.storeUrl; // URL pour enregistrer une nouvelle entité
        this.deleteUrl = config.deleteUrl; // URL pour supprimer une entité
        this.csrfToken = config.csrfToken; // Token CSRF pour les requêtes sécurisées

        this.createTitle = config.create_title; // Titre pour le formulaire d'ajout
        this.editTitle = config.edit_title; // Titre pour le formulaire de modification

        this.searchInputSelector = `${this.crudSelector} .crud-search-input`;
        this.paginationSelector = `${this.crudSelector} .pagination`;
        this.dataContainerSelector = `${this.tableSelector}`;
        this.filterIconSelector = `${ config.filterFormSelector} .filter-icon`;
        this.sortableColumnSelector = `${ config.tableSelector} .sortable-column`;

    }

    /**
     * Récupère une URL configurée avec un ID (ex : pour éditer ou afficher une entité spécifique).
     * @param {string} url - URL de base (ex : editUrl ou showUrl).
     * @param {string|number} id - Identifiant de l'entité.
     * @returns {string} - URL formatée avec l'ID.
     */
    getUrlWithId(url, id) {
        return url.replace(':id', id);
    }

    /**
     * Vérifie si la configuration est valide et complète.
     * @returns {boolean} - Retourne `true` si la configuration est valide.
     */
    isValid() {
        const requiredFields = [
            'crudSelector',
            'indexUrl',
            'createUrl',
            'showUrl',
            'editUrl',
            'storeUrl',
            'deleteUrl',
            'csrfToken',
            'entityName',
            'createTitle',
            'editTitle',
        ];

        return requiredFields.every((field) => this[field] !== undefined && this[field] !== null);
    }
}
