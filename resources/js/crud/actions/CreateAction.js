import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { ViewStateService } from '../components/ViewStateService';
import { NotificationHandler } from '../components/NotificationHandler';
import { Action } from './Action';
import EventUtil from '../utils/EventUtil';
import { CrudAction } from './CrudAction';
export class CreateAction extends CrudAction {

    constructor(config, tableUI) {
        super(config,tableUI);
        this.config = config;  
        this.tableUI = tableUI;
        this.SuscesMessage = 'Nouvelle entité ajoutée avec succès.';
    }

    init(){
        this.handleAddEntity();
    } 

    /**
     * Gère l'ouverture du modal et l'ajout d'une nouvelle entité.
     */
    addEntity() {

        this.createUrl = this.appendParamsToUrl(
            this.config.createUrl,
            this.viewStateService.getContextParams()
        );
        
        // Afficher le chargement dans le modal
        this.tableUI.indexUI.modalUI.showLoading(this.config.createTitle);

        // Charger le formulaire d'ajout via une requête AJAX
        $.get(this.createUrl)
            .done((html) => {
                // Injecter le contenu dans le modal et afficher le formulaire
                this.tableUI.indexUI.modalUI.showContent(html);
                this.executeScripts(html);
                this.config.init();
                this.tableUI.indexUI.formUI.init(() => this.submitEntity());
            })
            .fail((xhr) => {
                AjaxErrorHandler.handleError(xhr, 'Erreur lors de l\'ajout.');
            });
            
    }

       /**
         * Soumet le formulaire de modification via AJAX.
         */
        submitEntity(onSuccess) {
            const form = $(this.config.formSelector);
            const actionUrl = form.attr('action'); // URL définie dans le formulaire
            const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP
            const formData = form.serialize(); // Sérialisation des données du formulaire
            this.tableUI.indexUI.formUI.loader.show();
    
            // Valider le formulaire avant la soumission
            if (!this.tableUI.indexUI.formUI.validateForm()) {
                NotificationHandler.showError('Validation échouée. Veuillez corriger les erreurs.');
                this.tableUI.indexUI.formUI.loader.hide();
                return; // Ne pas soumettre si la validation échoue
            }
    
            // Envoyer les données via une requête AJAX
            $.ajax({
                url: actionUrl,
                method: method,
                data: formData
            })
                .done((data) => {
                    
                    // Affichage de message de progression de traitement
                    const traitement_token = data.data?.traitement_token;
                    if (traitement_token) {
                        this.pollTraitementStatus(traitement_token, () => {
                           this.handleSuccess(this.SuscesMessage);
                        });
                    }else{
                       this.handleSuccess(this.SuscesMessage);
                    }



                    this.tableUI.indexUI.formUI.loader.hide();
                    
                    this.tableUI.indexUI.modalUI.close(); // Fermer le modal après succès

                     // Appeler le callback de succès si fourni
                     if (typeof onSuccess === 'function') {
                        onSuccess();
                    }

                    this.handleAfterCreateAction(data);

                    // if(this.config.edit_has_many && this.config.afterCreateAction != ''){
                    //     const entity_id = parseInt( data.data[`entity_id`]);
                    //     this.tableUI.entityEditor.editEntity(entity_id);
                    //     this.tableUI.entityLoader.loadEntities();

                    // }else{
                    //     this.tableUI.entityLoader.loadEntities(); // Recharger les entités
                    // }
                   
                   
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
     * Gère les événements liés à l'ajout d'une entité.
     */
    handleAddEntity() {
        EventUtil.bindEvent('click', `${this.config.crudSelector} .addEntityButton`, (e) => {
            e.preventDefault();
            this.addEntity();
        });
    }

    /**
     * Gère l'action après création d'une entité selon afterCreateAction.
     * - "" + edit_has_many = true → 'update'
     * - index → recharge la liste
     * - edit  → ouvre l'éditeur + recharge
     * - update → idem edit
     * - custom:<route> → route spécifique
     *
     * @param {object} data - Données de la réponse backend (avec entity_id)
     */
    handleAfterCreateAction(data = {}) {
    const rawId = data?.data?.entity_id ?? data?.entity_id ?? data?.id;
    const entityId = Number.parseInt(rawId, 10);
    const hasValidId = Number.isInteger(entityId) && entityId > 0;

    // Normalisation de l'action
    let action = (this.config?.afterCreateAction || '').trim().toLowerCase();

    // Règle spéciale : si vide et edit_has_many → update
    if (!action && this.config?.edit_has_many) {
        action = 'update';
    }

    const reloadIndex = () => this.tableUI?.entityLoader?.loadEntities?.();

    switch (action) {
        case 'edit':
        case 'update':
        if (hasValidId) {
            this.tableUI?.entityEditor?.editEntity?.(entityId);
        }
        reloadIndex();
        break;

        case 'index':
        case '':
        default:
        reloadIndex();
        break;
    }
    }


}
