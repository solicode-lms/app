import $ from 'jquery';
import Swal from 'sweetalert2'

export default class GenericCrud {
    constructor(config) {
        this.fetchUrl = config.fetchUrl;      // URL pour charger les entités
        this.storeUrl = config.storeUrl;      // URL pour ajouter une entité
        this.deleteUrl = config.deleteUrl;    // Base URL pour supprimer une entité
        this.csrfToken = config.csrfToken;    // Jeton CSRF pour Laravel
        this.tableSelector = config.tableSelector; // Sélecteur du tableau HTML
        this.formSelector = config.formSelector;   // Sélecteur du formulaire HTML
        this.modalSelector = config.modalSelector; // Sélecteur du modal HTML
    }

    /**
     * Charger les entités depuis le backend et mettre à jour le tableau.
     */
    loadEntities() {
        $.get(this.fetchUrl, (html) => {
            $(this.tableSelector).html(html); // Injecte le HTML reçu dans le tableau
        }).fail(() => {
            alert('Erreur lors du chargement des données.');
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

            let formData = form.serialize(); // Sérialise les données du formulaire
        //  formData = { 
        //         ...formData, 
        //         _token: this.csrfToken 
        //     }
            $.post(this.storeUrl, formData)
            .done(() => {

                Swal.fire({
                    title: 'Succès !',
                    text: 'L\'opération a été effectuée avec succès.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });


                modal.modal('hide'); // Ferme le modal
                form[0].reset();     // Réinitialise le formulaire
                this.loadEntities(); // Recharge les entités
            })
            .fail((xhr) => {
                alert('Erreur lors de l\'ajout : ' + xhr.responseJSON.message);
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


            if (confirm('Êtes-vous sûr de vouloir supprimer cette entité ?')) {
                $.ajax({
                    url: `${deleteUrl}`,
                    method: 'DELETE',
                    data: { _token: this.csrfToken },
                })
                .done(() => {

                    Swal.fire({
                        title: 'Succès !',
                        text: 'L\'opération a été effectuée avec succès.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        toast: true
                    });


                    this.loadEntities(); // Recharge les entités après suppression
                })
                .fail((xhr) => {
                    alert('Erreur lors de la suppression : ' + xhr.responseJSON.message);
                });
            }
        });
    }

    /**
     * Initialiser les actions CRUD.
     */
    init() {
        // this.loadEntities(); // Charge les entités au chargement de la page
        this.addEntity();    // Gère l'ajout d'une entité
        this.deleteEntity(); // Gère la suppression d'une entité
    }
}
