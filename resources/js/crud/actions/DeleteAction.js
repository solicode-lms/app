import { Action } from './Action';
import { NotificationHandler } from '../components/NotificationHandler';
import { LoadListAction } from './LoadListAction';
import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import EventUtil from '../utils/EventUtil';
import { CrudAction } from './CrudAction';

export class DeleteAction extends CrudAction {

    constructor(config, tableUI) {
         super(config,tableUI);
        this.config = config;
        this.tableUI = tableUI;
       
        
       
        this.suscesMessage = 'Entité supprimée avec succès.';
       
    }

    init() {
        this.handleDeleteEntity();
    }

    /**
     * Supprime une entité via AJAX.
     * @param {number|string} id - Identifiant de l'entité à supprimer.
     */
    deleteEntity(id) {
        let deleteUrl = this.getUrlWithId(this.config.deleteUrl, id);
        deleteUrl = this.appendParamsToUrl(deleteUrl, this.viewStateService.getContextParams());
    
        this.loader.showNomBloquante();
        NotificationHandler.confirmAction(
            'Êtes-vous sûr ?', 'Cette action est irréversible.',
            () => {
                NotificationHandler.showToast('info','Suppression en cours...');
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    data: { _token: this.config.csrfToken }
                }).done((response) => {

                     // Affichage de message de progression de traitement
                    const traitement_token = response?.data?.traitement_token;
                    if (traitement_token) {
                        this.pollTraitementStatus(traitement_token, () => {
                           this.handleSuccess(this.suscesMessage);
                        });
                    }else{
                        this.loader.hide();
                        this.handleSuccess(this.suscesMessage);
                    }


                    $(`#${this.config.entity_name}-row-${id}`).fadeOut(); // Supprime la ligne sans recharger
                  
                })
                
                .fail((xhr) => {
                    AjaxErrorHandler.handleError(xhr, "Erreur lors de la suppression de l'entité.");
                });
            }
        );
    }

    /**
     * Gère les événements liés à la suppression d'une entité.
     */
    handleDeleteEntity() {
        EventUtil.bindEvent('click', `${this.config.crudSelector} .deleteEntity`, (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).data('id'); // Récupérer l'ID de l'entité
            this.deleteEntity(id);
        });
    }
}
