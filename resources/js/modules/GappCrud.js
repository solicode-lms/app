import $ from 'jquery';
import Swal from 'sweetalert2';

export default class GappCrud {
    constructor(config) {
        this.fetchUrl = config.fetchUrl;      // URL pour charger les entités
        this.storeUrl = config.storeUrl;      // URL pour ajouter une entité
        this.deleteUrl = config.deleteUrl;    // Base URL pour supprimer une entité
        this.csrfToken = config.csrfToken;    // Jeton CSRF pour Laravel
        this.tableSelector = config.tableSelector; // Sélecteur du tableau HTML
        this.formSelector = config.formSelector;   // Sélecteur du formulaire HTML
        this.modalSelector = config.modalSelector; // Sélecteur du modal HTML
        this.entity_name = config.entity_name
    }

   /**
     * Initialiser les actions CRUD.
     */
    init() {
        this.addEntity();    // Gère l'ajout d'une entité
        this.deleteEntity(); // Gère la suppression d'une entité
        this.setupModalCancelButton(); // Active le bouton de fermeture du modal
    }
    /**
     * Afficher une notification toast.
     * @param {string} type - Le type de message (success, error, warning, info).
     * @param {string} message - Le message à afficher.
     */
    showToast(type, message) {
        Swal.fire({
            toast: true,
            position: 'top-end', // Position en haut à droite
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000, // Durée d'affichage en millisecondes
            timerProgressBar: true,
        });
    }


    /**
     * Gestion de la fermeture du modal via le bouton .cancelEdit
     */
      setupModalCancelButton() {

        $(document).on('click', `#${this.entity_name}_form_cancel` , (e) => {
            e.preventDefault(); // Empêche l'action par défaut du bouton, si nécessaire
            $(this.modalSelector).modal('hide'); // Ferme le modal
        });

        // change css 
        $(`${this.formSelector} .card-footer`).each(function () {
            $(this).removeClass('card-footer').addClass('modal-footer');
        });
    }


    /**
     * Charger les entités depuis le backend et mettre à jour le tableau.
     */
    loadEntities() {
        $.get(this.fetchUrl)
            .done((html) => {
                $(this.tableSelector).html(html); // Injecte le HTML reçu dans le tableau
                this.showToast('success', 'Données chargées avec succès.');
            })
            .fail(() => {
                this.showToast('error', 'Erreur lors du chargement des données.');
            });
    }

    /**
     * Ajouter une nouvelle entité via un formulaire et recharger les entités après ajout.
     */
    addEntity() {
        const form = $(this.formSelector);
        const modal = $(this.modalSelector);


        form.submit((e) => {
            e.preventDefault(); // Empêche le rechargement de la page

            const formData = form.serialize(); // Sérialise les données du formulaire

            $.post(this.storeUrl, formData)
                .done(() => {
                    this.showToast('success', 'Entité ajoutée avec succès.');
                    modal.modal('hide'); // Ferme le modal
                    form[0].reset();     // Réinitialise le formulaire
                    this.loadEntities(); // Recharge les entités
                })
                .fail((xhr) => {
                    const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite.';
                    this.showToast('error', `Erreur lors de l'ajout : ${errorMessage}`);
                });
        });
    }

    /**
     * Supprimer une entité via son ID et recharger les entités après suppression.
     */
    deleteEntity() {
        $(document).on('click', '.deleteEntity', (e) => {
            const id = $(e.currentTarget).data('id'); // Récupère l'ID de l'entité
            e.preventDefault(); // Empêche le rechargement de la page

            const deleteUrl = this.deleteUrl.replace(':id', id);

            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        data: { _token: this.csrfToken },
                    })
                        .done(() => {
                            this.showToast('success', 'Entité supprimée avec succès.');
                            this.loadEntities(); // Recharge les entités après suppression
                        })
                        .fail((xhr) => {
                            const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite.';
                            this.showToast('error', `Erreur lors de la suppression : ${errorMessage}`);
                        });
                }
            });
        });
    }

 
}
