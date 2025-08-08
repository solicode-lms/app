import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { ViewStateService } from '../components/ViewStateService';
import { NotificationHandler } from '../components/NotificationHandler';
import { Action } from './Action';
import EventUtil from '../utils/EventUtil';
export class CreateAction extends Action {

    constructor(config, tableUI) {
        super(config);
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
                data: formData,
            })
                .done((data) => {
                    const traitement_token = data.data?.traitement_token;
                    if (traitement_token) {
                        this.pollTraitementStatus(traitement_token); // Appelle ton polling
                    }
                    
                    this.tableUI.indexUI.formUI.loader.hide();
                    this.handleSuccess(this.SuscesMessage);
                    this.tableUI.indexUI.modalUI.close(); // Fermer le modal après succès

                     // Appeler le callback de succès si fourni
                     if (typeof onSuccess === 'function') {
                        onSuccess();
                    }

                    if(this.config.edit_has_many){

                        const entity_id = parseInt( data.data[`entity_id`]);

                        this.tableUI.entityEditor.editEntity(entity_id);
                        this.tableUI.entityLoader.loadEntities();

                        // // redirect to edit 
                        // let editUrl = this.getUrlWithId(this.config.editUrl, entity_id); // Générer l'URL dynamique
                        // editUrl = this.appendParamsToUrl(
                        //     editUrl,
                        //     this.viewStateService.getContextParams()
                        // );

                        // window.location.href  = editUrl;


                    }else{
                        this.tableUI.entityLoader.loadEntities(); // Recharger les entités
                    }
                   
                   
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
     * Surveille l'état d'un traitement différé côté serveur (polling).
     * @param {string} token - Le token unique du traitement (généré après création).
     * @param {function} onDoneCallback - Fonction à appeler quand le traitement est terminé.
     */
    pollTraitementStatus(token, onDoneCallback = null) {
        const interval = setInterval(() => {
            $.get('/admin/traitement/status/' + token, function (res) {
                const status = res.status;

                if (status === 'done') {
                    clearInterval(interval);
                    NotificationHandler.showSuccess('✅ Traitement terminé.');
                    if (typeof onDoneCallback === 'function') {
                        onDoneCallback();
                    } else {
                        location.reload();
                    }
                } else if (status.startsWith('error')) {
                    clearInterval(interval);
                    NotificationHandler.showError('❌ Erreur pendant le traitement : ' + status);
                } else {
                    console.log('⏳ Traitement en cours...');
                }
            });
        }, 2000); // ➕ Tu peux ajuster la fréquence si besoin
    }

}
