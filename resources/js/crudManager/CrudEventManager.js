export class CrudEventManager {
    /**
     * Constructeur de la classe CrudEventManager.
     * @param {Object} config - Configuration des sélecteurs CRUD.
     * @param {CrudActions} actions - Instance de CrudActions pour exécuter les actions CRUD.
     */
    constructor(config, actions) {
        this.config = config;
        this.actions = actions;
    }

    /**
     * Initialise les gestionnaires d'événements pour les actions CRUD.
     */
    init() {
        this.handleAddEntity();
        this.handleEditEntity();
        this.handleShowEntity();
        this.handleDeleteEntity();
        this.handleSubmitForm();
    }

    handleSubmitForm() {
        $(document).on('submit', this.config.formSelector, (e) => {
            e.preventDefault(); // Empêche le rechargement de la page
            this.actions.submitForm(); // Appelle la méthode de `CrudActions`
        });
    }


    /**
     * Gère les événements liés à l'ajout d'une entité.
     */
    handleAddEntity() {
        $(document).on('click', `${this.config.crudSelector} .addEntityButton`, (e) => {
            e.preventDefault();
            this.actions.addEntity();
        });
    }

    /**
     * Gère les événements liés à la modification d'une entité.
     */
    handleEditEntity() {
        $(document).on('click', `${this.config.crudSelector} .editEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.actions.editEntity(id);
        });
    }

    /**
     * Gère les événements liés à l'affichage des détails d'une entité.
     */
    handleShowEntity() {
        $(document).on('click', `${this.config.crudSelector} .showEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.actions.showEntity(id);
        });
    }

    /**
     * Gère les événements liés à la suppression d'une entité.
     */
    handleDeleteEntity() {
        $(document).on('click', `${this.config.crudSelector} .deleteEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.actions.deleteEntity(id);
        });
    }
}
