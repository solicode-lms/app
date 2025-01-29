import { Action } from './Action';
import { NotificationHandler } from '../components/NotificationHandler';
import { LoadListAction } from './LoadListAction';

export class DeleteAction extends Action {

    constructor(config) {
        super(config);
       
        this.suscesMessage = 'Entité supprimée avec succès.';
       
    }

    /**
     * Supprime une entité via AJAX.
     * @param {number|string} id - Identifiant de l'entité à supprimer.
     */
    deleteEntity(id) {
        let deleteUrl = this.getUrlWithId(this.config.deleteUrl, id);
        deleteUrl = this.appendParamsToUrl(deleteUrl, this.contextService.getContextParams());
    
        NotificationHandler.confirmAction(
            'Êtes-vous sûr ?', 'Cette action est irréversible.',
            () => {
                NotificationHandler.showInfo('Suppression en cours...');
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    data: { _token: this.config.csrfToken }
                }).done(() => {
                    $(`#${this.config.entity_name}-row-${id}`).fadeOut(); // Supprime la ligne sans recharger
                    this.handleSuccess(this.suscesMessage);
                }).fail((xhr) => {
                    this.handleError(xhr.responseJSON?.message || "Erreur lors de la suppression.");
                });
            }
        );
    }
}
