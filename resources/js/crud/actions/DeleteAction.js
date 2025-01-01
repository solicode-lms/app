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
        
        let deleteUrl = this.getUrlWithId(this.config.deleteUrl, id); // Générer l'URL dynamique
        deleteUrl = this.appendParamsToUrl(
            deleteUrl,
            this.contextManager.getContextParams()
        );

        // Confirmer l'action avant de procéder
        NotificationHandler.confirmAction(
            'Êtes-vous sûr ?',
            'Cette action est irréversible.',
            () => {
                // Afficher un message d'information pendant la suppression
                NotificationHandler.showInfo('Suppression en cours...');
                // Envoyer une requête DELETE
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    data: { _token: this.config.csrfToken }, // Inclure le jeton CSRF
                })
                    .done(() => {
                        this.handleSuccess(this.suscesMessage);
                      
                        this.entityLoader.loadEntities(); // Recharger les entités après suppression
                    })
                    .fail((xhr) => {
                        const errorMessage = xhr.responseJSON?.message || "Erreur lors de la suppression de l\'entité."
                        this.handleError(errorMessage);
                    });
            }
        );
    }
}
