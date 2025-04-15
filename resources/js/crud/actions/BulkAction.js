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

            const selectedIds = this.getSelectedIds();
            const selectedCount = selectedIds.length;

            if (selectedCount === 0) {
                NotificationHandler.showError('Veuillez sélectionner au moins une ligne.');
                return;
            }

            const doAction = () => {
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
            };

            if (confirmText) {
                NotificationHandler.confirmAction('Confirmation', `${confirmText} (${selectedCount} élément(s))`, doAction);
            } else {
                doAction();
            }
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
