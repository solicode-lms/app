import $ from 'jquery';
import { GappMessages } from './GappMessages';
import { showLoading, hideLoading } from './GappLoading';

export default class GappCrud {
    constructor(config) {
        this.indexUrl = config.indexUrl;
        this.createUrl = config.createUrl;
        this.showUrl = config.showUrl;
        this.editUrl = config.editUrl;
        this.storeUrl = config.storeUrl;
        this.deleteUrl = config.deleteUrl;
        this.csrfToken = config.csrfToken;
        this.tableSelector = config.tableSelector;
        this.formSelector = config.formSelector;
        this.modalSelector = config.modalSelector;
        this.entity_name = config.entity_name;

        this.crudSelector = `#${this.entity_name}_crud`;
    }

    /**
     * Initialiser les actions CRUD.
     */
    init() {
        this.editEntity();
        this.addEntity();
        this.deleteEntity();
        this.initForm();
        this.showEntity();
    }

    /**
     * Gérer la fermeture du modal via le bouton d'annulation.
     */
    initForm() {
        $(document).on('click', `#${this.entity_name}_form_cancel`, (e) => {
            e.preventDefault();
            $(this.modalSelector).modal('hide');

           
        });

         // Modifier le style des footers
         $(`${this.formSelector} .card-footer`).each(function () {
            $(this).removeClass('card-footer').addClass('modal-footer');
        });
    }


    initFormToReadOnly() {
        const form = $(this.formSelector);
    
        // Parcourir tous les champs du formulaire pour les rendre en lecture seule
        form.find('input, select, textarea, button').each(function () {
            const element = $(this);
    
            if (element.is('input') || element.is('textarea')) {
                element.attr('readonly', true); // Rendre les champs de saisie non modifiables
            } else if (element.is('select')) {
                element.attr('disabled', true); // Désactiver les listes déroulantes
            } else if (element.is('button')) {
                element.attr('disabled', true); // Désactiver les boutons
            }
        });
    
        // Supprimer les boutons spécifiques (optionnel)
        form.find('.btn').not(`#${this.entity_name}_form_cancel`).addClass('d-none'); // Masquer les boutons avec la classe .btn
    }
    

    /**
     * Charger les entités depuis le backend et mettre à jour le tableau.
     */
    loadEntities() {
        showLoading();
        $.get(this.indexUrl)
            .done((html) => {
                $(this.tableSelector).html(html);
                hideLoading();
                // GappMessages.showToast('success', 'Données chargées avec succès.');
            })
            .fail(() => {
                GappMessages.showToast('error', 'Erreur lors du chargement des données.');
            });
    }

    addEntity() {
        $(document).on('click', `${this.crudSelector} .addEntityButton`, (e) => {
            e.preventDefault();

            // Récupérer le formulaire d'ajout via AJAX
            $.get(this.createUrl)
                .done((html) => {
                    const modal = $(this.modalSelector);
    
                    // Injecter le formulaire dans le modal
                    modal.find('.modal-content').html(html);
    
                    this.initForm();

                    // Ouvrir le modal pour l'ajout
                    modal.modal('show');
                })
                .fail(() => {
                    GappMessages.showToast('error', 'Erreur lors du chargement du formulaire d\'ajout.');
                });
        });
    
        // Gestion de la soumission du formulaire
        $(document).on('submit', this.formSelector, (e) => {
            e.preventDefault();
    
            const form = $(e.currentTarget);
            const actionUrl = form.attr('action'); // URL définie dans le formulaire
            const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP (POST pour l'ajout)
    
            const formData = form.serialize();
    
            $.ajax({
                url: actionUrl,
                method: method,
                data: formData,
            })
                .done(() => {
                    const successMessage = 'Entité ajoutée avec succès.';
                    GappMessages.showToast('success', successMessage);
    
                    $(this.modalSelector).modal('hide'); // Ferme le modal
                    this.loadEntities(); // Recharge le tableau
                })
                .fail((xhr) => {
                    const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite.';
                    GappMessages.showToast('error', `Erreur lors de l'ajout : ${errorMessage}`);
                });
        });
    }
    

    /**
     * Supprimer une entité via son ID et recharger les entités après suppression.
     */
    deleteEntity() {
        $(document).on('click', `${this.tableSelector} .deleteEntity`, (e) => {
          
            e.preventDefault();

            const id = $(e.currentTarget).data('id');
            const deleteUrl = this.deleteUrl.replace(':id', id);

            GappMessages.confirmAction(
                'Êtes-vous sûr ?',
                'Cette action est irréversible.',
                () => {
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        data: { _token: this.csrfToken },
                    })
                        .done(() => {
                            GappMessages.showToast('success', 'Entité supprimée avec succès.');
                            this.loadEntities();
                        })
                        .fail((xhr) => {
                            const errorMessage = xhr.responseJSON?.message || 'Une erreur s\'est produite.';
                            GappMessages.showToast('error', `Erreur lors de la suppression : ${errorMessage}`);
                        });
                }
            );
        });
    }

    editEntity() {
        $(document).on('click',`${this.crudSelector} .editEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité


            const editUrl = this.editUrl.replace(':id', id);

    
            // Récupérer le formulaire via AJAX
            $.get(editUrl)
                .done((html) => {
                    const modal = $(this.modalSelector);
    
                    // Injecter le formulaire dans le modal
                    modal.find('.modal-content').html(html);
    
                    // Ouvrir le modal pour modification
                    modal.modal('show');
                })
                .fail(() => {
                    GappMessages.showToast('error', 'Erreur lors du chargement du formulaire.');
                });
        });
    }
    

    showEntity() {
        $(document).on('click', `${this.crudSelector} .showEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            const showUrl = this.showUrl.replace(':id', id); // URL pour afficher les détails
    
            // Récupérer les détails via AJAX
            $.get(showUrl)
                .done((html) => {
                    const modal = $(this.modalSelector);
    
                    // Injecter le contenu dans le modal
                    modal.find('.modal-content').html(html);
    
                    this.initFormToReadOnly();
                    // Ouvrir le modal pour afficher les détails
                    modal.modal('show');
                })
                .fail(() => {
                    GappMessages.showToast('error', 'Erreur lors du chargement des détails de l\'entité.');
                });
        });
    }
    
}
