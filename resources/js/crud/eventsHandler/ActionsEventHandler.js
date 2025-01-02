export class ActionsEventHandler {
    /**
     * Constructeur de la classe ActionsEventHandler.
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
        if(this.config.edit_has_many){
            this.handleShowEntity();
            this.handleDeleteEntity();
            this.handleSubmitForm();
            this.handleButtonSaveCardWithHasMany();
        }else{
            this.handleEditEntity();
            this.handleAddEntity();
            this.handleShowEntity();
            this.handleDeleteEntity();
            this.handleSubmitForm();
        }
    }

    handleButtonSaveCardWithHasMany(){
        $(document).on('click', `${this.config.crudSelector} .btn-card-header`, (e) => {
            e.preventDefault();
            this.actions.editor.submitEntityAndRedirect(this.config.indexUrl);
         

        });
    }
    handleSubmitForm() {
        $(document).on('submit', this.config.formSelector, (e) => {
            e.preventDefault(); // Empêche le rechargement de la page
            this.actions.editor.submitEntity(); // Appelle la méthode de `CrudActions`
        });
    }


    /**
     * Gère les événements liés à l'ajout d'une entité.
     */
    handleAddEntity() {
        $(document).on('click', `${this.config.crudSelector} .addEntityButton`, (e) => {
            e.preventDefault();
            this.actions.creator.addEntity();
        });
    }

    /**
     * Gère les événements liés à la modification d'une entité.
     */
    handleEditEntity() {
        $(document).on('click', `${this.config.crudSelector} .editEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.actions.editor.editEntity(id);
        });
    }

    /**
     * Gère les événements liés à l'affichage des détails d'une entité.
     */
    handleShowEntity() {
        $(document).on('click', `${this.config.crudSelector} .showEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.actions.viewer.showEntity(id);
        });
    }

    /**
     * Gère les événements liés à la suppression d'une entité.
     */
    handleDeleteEntity() {
        $(document).on('click', `${this.config.crudSelector} .deleteEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.actions.deleter.deleteEntity(id);
        });
    }
}
