import { BaseAction } from './BaseAction';
import EventUtil from '../utils/EventUtil';
import { NotificationHandler } from '../components/NotificationHandler';
import { AjaxErrorHandler } from '../components/AjaxErrorHandler';

export class BulkAction extends BaseAction {

    constructor(config, tableUI) {
        super(config);
        this.config = config;
        this.tableUI = tableUI;
        this.successMessage = 'Action en masse effectuée avec succès.';
    }

    init() {
        this.bindBulkActionButton();
    }

    /**
     * Initialise l'événement sur les boutons bulk-action.
     */
    bindBulkActionButton() {
        EventUtil.bindEvent('click', `${this.config.crudSelector} .bulkActionButton`, (e) => {
            e.preventDefault();

            const button = e.currentTarget;
            const url = button.dataset.url;
            const method = button.dataset.method || 'POST';
            const confirmText = button.dataset.confirm || null;
            const actionType = button.dataset.actionType || 'ajax';

            const selectedIds = this.getSelectedIds();
            const selectedCount = selectedIds.length;

            if (selectedCount === 0) {
                NotificationHandler.showError('Veuillez sélectionner au moins une ligne.');
                return;
            }

            const doAction = () => {
                if (actionType === 'modal') {
                    // Affichage d'un formulaire d'édition en masse dans une modale
                    const finalUrl = this.appendParamsToUrl(url, this.viewStateService.getContextParams());

                    this.tableUI.indexUI.modalUI.showLoading('Modification en masse...');

                    $.get(finalUrl, { ids: selectedIds })
                        .done((html) => {
                            this.tableUI.indexUI.modalUI.showContent(html);
                            this.executeScripts(html);
                         
                            this.tableUI.indexUI.modalUI.setTitle(window.modalTitle);
                            this.tableUI.indexUI.formUI.init(() => this.submitEntity(), false);
                            this.tableUI.indexUI.formUI.disableRequiredAttributes();

                            // Active la checkbox correspondante à tout champ modifié
                            EventUtil.bindEvent('change', `${this.config.formSelector} input, ${this.config.formSelector} select, ${this.config.formSelector} textarea`, (event) => {
                                const input = event.currentTarget;
                                const fieldName = input.name?.replace('[]', '');

                                // Si le champ a un nom et une case à cocher correspondante
                                if (fieldName) {
                                    const checkbox = document.getElementById(`bulk_field_${fieldName}`);
                                    if (checkbox) {
                                        checkbox.checked = true;
                                    }
                                }
                            });

                        })
                        .fail((xhr) => {
                            this.tableUI.indexUI.modalUI.close();
                            AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement du formulaire.');
                        });
                } else {
                    // Traitement AJAX classique
                    NotificationHandler.showToast('info', `Traitement de ${selectedCount} élément(s) en cours...`);

                    $.ajax({
                        url: this.appendParamsToUrl(url, this.viewStateService.getContextParams()),
                        method: method,
                        data: {
                            ids: selectedIds,
                            _token: this.config.csrfToken
                        }
                    })
                        .done((data) => {
                            NotificationHandler.show(data.type, data.title, data.message || `${selectedCount} élément(s) traité(s).`);
                            this.tableUI.entityLoader.loadEntities();
                        })
                        .fail((xhr) => {
                            AjaxErrorHandler.handleError(xhr, 'Erreur lors de l\'action en masse.');
                        });
                }
            };

            if (confirmText) {
                NotificationHandler.confirmAction('Confirmation', `${confirmText} (${selectedCount} élément(s))`, doAction);
            } else {
                doAction();
            }
        });
    }

    /**
     * Envoie le formulaire en AJAX depuis le formulaire en masse.
     */
    submitEntity(onSuccess) {
        const form = $(this.config.formSelector);
        const actionUrl = form.attr('action');
        const method = form.find('input[name="_method"]').val() || 'POST';
        const formData = form.serialize();

        this.tableUI.indexUI.formUI.loader.show();

        if (!this.tableUI.indexUI.formUI.validateForm()) {
            NotificationHandler.showError('Validation échouée. Veuillez corriger les erreurs.');
            this.tableUI.indexUI.formUI.loader.hide();
            return;
        }

        $.ajax({
            url: actionUrl,
            method: method,
            data: formData
        })
            .done((data) => {
                this.tableUI.indexUI.formUI.loader.hide();
                NotificationHandler.show(data.type, data.title, data.message);
                this.tableUI.indexUI.modalUI.close();
                if (typeof onSuccess === 'function') {
                    onSuccess();
                }
                this.tableUI.entityLoader.loadEntities();
            })
            .fail((xhr) => {
                this.tableUI.indexUI.formUI.loader.hide();

                if (xhr.responseJSON?.errors) {
                    this.tableUI.indexUI.formUI.showFieldErrors(xhr.responseJSON.errors);
                }

                AjaxErrorHandler.handleError(xhr, "Erreur lors du traitement du formulaire.");
            });
    }

    /**
     * Récupère les IDs sélectionnés dans le tableau.
     * @returns {Array} Liste des IDs sélectionnés.
     */
    getSelectedIds() {
        const checkboxes = document.querySelectorAll(`${this.config.crudSelector} .check-row:checked`);
        return Array.from(checkboxes).map(cb => cb.dataset.id);
    }
}