import { BaseAction } from '../BaseAction';

export class EntityDeleter extends BaseAction {
    /**
     * Supprime une entité via AJAX.
     * @param {number|string} id - Identifiant de l'entité à supprimer.
     */
    deleteEntity(id) {
        const deleteUrl = this.getUrlWithId(this.config.deleteUrl, id); // Générer l'URL dynamique

        // Confirmer l'action avant de procéder
        MessageHandler.showConfirmation(
            'Êtes-vous sûr ?',
            'Cette action est irréversible.',
            () => {
                // Afficher un message d'information pendant la suppression
                this.handleInfo('Suppression en cours...');

                // Envoyer une requête DELETE
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    data: { _token: this.config.csrfToken }, // Inclure le jeton CSRF
                })
                    .done(() => {
                        this.handleSuccess('Entité supprimée avec succès.');
                        this.loader.loadEntities(); // Recharger les entités après suppression
                    })
                    .fail(() => {
                        this.handleError('Erreur lors de la suppression de l\'entité.');
                    });
            }
        );
    }
}
