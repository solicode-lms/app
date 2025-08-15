import { Action } from './Action';
import { NotificationHandler } from '../components/NotificationHandler';
import { LoadListAction } from './LoadListAction';
import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import EventUtil from '../utils/EventUtil';

/**
 * Classe représentant une action sur une entité.
 */
export class EntityAction extends Action {

    /**
     * Constructeur de la classe EntityAction.
     * @param {object} config - Configuration des actions.
     * @param {object} tableUI - Interface utilisateur du tableau concerné.
     */
    constructor(config, tableUI) {
        super(config);
        this.config = config;
        this.tableUI = tableUI;
        this.successMessage = 'Opération effectuée avec succès.';
    }

    /**
     * Initialisation des actions sur les entités.
     */
    init() {
        this.bindEntityActions();
    }

    /**
     * Exécute une action sur une entité en fonction de son type.
     * @param {string} actionType - Type de l'action (form ou confirm).
     * @param {string} actionUrl - URL d'action extraite de l'attribut data-url.
     */
    executeAction(actionType, actionUrl) {
        actionUrl = this.appendParamsToUrl(actionUrl, this.viewStateService.getContextParams());
        
        if (actionType === 'confirm') {
            NotificationHandler.confirmAction(
                'Confirmation requise', 'Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?',
                () => {
                    NotificationHandler.showToast('info', 'Action en cours...');
                    $.ajax({
                        url: actionUrl,
                        method: 'GET',
                        data: { _token: this.config.csrfToken }
                    }).done((data) => {
                        NotificationHandler.show(data.type,data.title,data.message);
                        this.tableUI.loadListAction.loadEntities();
                    }).fail((xhr) => {
                        AjaxErrorHandler.handleError(xhr, "Erreur lors de l'exécution de l'action sur l'entité.");
                    });
                }
            );
        } else if (actionType === 'form') {
            this.loadForm(actionUrl);
            
        } else if (actionType === 'downloadMode') {
            NotificationHandler.showToast('info', 'Téléchargement en cours...');
            
            // Générer l'URL avec les paramètres de contexte
            const finalUrl = this.appendParamsToUrl(actionUrl, this.viewStateService.getContextParams());

            // ✅ Créer un lien temporaire et déclencher le téléchargement
            const link = document.createElement('a');
            link.href = finalUrl;
            link.setAttribute('download', ''); // Facultatif : permet d’indiquer que c’est un fichier
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            console.error("Type d'action inconnu :", actionType);
        }
    }

    /**
     * Charge un formulaire via AJAX et l'affiche dans un modal.
     * @param {string} actionUrl - URL du formulaire à charger.
     */
    loadForm(actionUrl) {
        this.tableUI.indexUI.modalUI.showLoading('Chargement...');
        
        $.get(actionUrl)
            .done((html) => {
                this.tableUI.indexUI.modalUI.showContent(html);
                this.executeScripts(html);
              
                this.tableUI.loadListAction.loadEntities(); 
            })
            .fail((xhr) => {
                AjaxErrorHandler.handleError(xhr, 'Erreur lors du chargement du formulaire.');
            });
    }

    /**
     * Soumet un formulaire via AJAX.
     */
    submitForm() {
        const form = $(this.config.formSelector);
        const actionUrl = form.attr('action'); // URL définie dans le formulaire
        const method = form.find('input[name="_method"]').val() || 'POST'; // Méthode HTTP
        const formData = form.serialize(); // Sérialisation des données du formulaire
        this.tableUI.indexUI.formUI.loader.show();
    
        if (!this.tableUI.indexUI.formUI.validateForm()) {
            NotificationHandler.showError('Validation échouée. Veuillez corriger les erreurs.');
            this.tableUI.indexUI.formUI.loader.hide();
            return;
        }
    
        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,
        })
        .done(() => {
            this.tableUI.indexUI.formUI.loader.hide();
            this.handleSuccess(this.successMessage);
            this.tableUI.indexUI.modalUI.close();
            this.tableUI.loadListAction.loadEntities();
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
     * Attache les événements d'action aux éléments concernés.
     */
    bindEntityActions() {
        EventUtil.bindEvent('click', `${this.config.crudSelector} .actionEntity`, (event) => {
            event.preventDefault();

           

            const entityId = $(event.currentTarget).data('id'); // Récupération de l'ID de l'entité
            const actionUrl = $(event.currentTarget).data('url'); // Récupération de l'URL d'action
            const actionType = $(event.currentTarget).data('action-type'); // Type d'action
            
            if (actionUrl && actionType) {
                this.executeAction(actionType, actionUrl);
            } else {
                console.error("URL ou type d'action non défini pour l'entité", entityId);
            }
        });
    }
}
