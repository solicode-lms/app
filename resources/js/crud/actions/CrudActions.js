
import { FormManager } from './../forms/FormManager';

export class CrudActions {
    /**
     * Constructeur de la classe CrudActions.
     * @param {Object} config - Configuration des URLs et sélecteurs CRUD.
     * @param {CrudModalManager} modalManager - Instance de CrudModalManager pour gérer les modals.
     * @param {CrudLoader} loader - Instance de CrudLoader pour gérer les indicateurs de chargement.
     * @param {Function} showToast - Fonction pour afficher des notifications (ex: GappMessages.showToast).
     */
    constructor(config, modalManager, loader, showToast) {
        this.config = config;
        this.modalManager = modalManager;
        this.loader = loader;
        this.showToast = showToast;
        this.formManager = new FormManager(this.config.formSelector,this.modalManager)
    }

    /**
     * Récupère et affiche les entités.
     */
    loadEntities() {
        if (this.loader.show()) {
            $.get(this.config.indexUrl)
                .done((html) => {
                    $(this.config.tableSelector).html(html);
                    this.showToast('success', 'Données chargées avec succès.');
                })
                .fail(() => {
                    this.showToast('error', 'Erreur lors du chargement des données.');
                })
                .always(() => {
                    this.loader.hide();
                });
        }
    }


    submitForm() {
        const form = $(this.config.formSelector);
        const actionUrl = form.attr('action'); // URL définie dans le formulaire
        const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP (POST par défaut)
        const formData = form.serialize(); // Sérialisation des données du formulaire
    
        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,
        })
            .done(() => {
                this.showToast('success', 'Opération réalisée avec succès.');
                this.modalManager.close(); // Ferme le modal

                this.loadEntities(); // Recharge les données dans la table
            })
            .fail((xhr) => {
                const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite.';
                this.showToast('error', `Erreur lors de la soumission : ${errorMessage}`);
            });
    }

    /**
     * Ouvre le modal pour créer une entité.
     */
    addEntity() {
        this.modalManager.showLoading(this.config.createTitle);
        $.get(this.config.createUrl)
            .done((html) => {
                this.modalManager.showContent(html);
                this.formManager.init();

            })
            .fail(() => {
                this.modalManager.showError('Erreur lors du chargement du formulaire d\'ajout.');
                this.showToast('error', 'Impossible de charger le formulaire d\'ajout.');
            });
    }

    /**
     * Ouvre le modal pour modifier une entité.
     * @param {number|string} id - ID de l'entité à modifier.
     */
    editEntity(id) {
        const editUrl = this.config.getUrlWithId(this.config.editUrl, id);
        this.modalManager.showLoading(this.config.editTitle);
        $.get(editUrl)
            .done((html) => {
                this.modalManager.showContent(html);
                this.formManager.init();
            })
            .fail(() => {
                this.modalManager.showError('Erreur lors du chargement du formulaire de modification.');
                this.showToast('error', 'Impossible de charger le formulaire de modification.');
            });
    }

    /**
     * Affiche les détails d'une entité.
     * @param {number|string} id - ID de l'entité à afficher.
     */
    showEntity(id) {
        const showUrl = this.config.getUrlWithId(this.config.showUrl, id);
        this.modalManager.showLoading('Détails de l\'entité');
        $.get(showUrl)
            .done((html) => {
                this.modalManager.showContent(html);
            })
            .fail(() => {
                this.modalManager.showError('Erreur lors du chargement des détails.');
                this.showToast('error', 'Impossible de charger les détails de l\'entité.');
            });
    }

    /**
     * Supprime une entité.
     * @param {number|string} id - ID de l'entité à supprimer.
     */
    deleteEntity(id) {
        const deleteUrl = this.config.getUrlWithId(this.config.deleteUrl, id);
        this.showToast('info', 'Suppression en cours...');
        $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            data: { _token: this.config.csrfToken },
        })
            .done(() => {
                this.showToast('success', 'Entité supprimée avec succès.');
                this.loadEntities(); // Recharger les entités après suppression
            })
            .fail(() => {
                this.showToast('error', 'Erreur lors de la suppression de l\'entité.');
            });
    }
}
