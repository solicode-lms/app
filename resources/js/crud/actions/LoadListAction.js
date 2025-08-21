import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { LoadingIndicator } from '../components/LoadingIndicator';
import { NotificationHandler } from '../components/NotificationHandler';
import { BaseAction } from './BaseAction';
import EventUtil from '../utils/EventUtil';
import ArrayUtil from './../utils/ArrayUtil';

export class LoadListAction extends BaseAction {
    /**
     * Constructeur de LoadListAction.
     * @param {Object} config - Configuration pour le chargement des entités.
     */
    constructor(config, tableUI) {

        super(config);
        
        this.config = config;
        this.tableUI = tableUI;
        this.indexUrl = config.indexUrl;

        /**
         * Compteur incrémental pour identifier les requêtes.
         * Permet de distinguer la dernière requête envoyée
         * et d’ignorer les réponses "en retard".
         *
         * 🛑 Problème :
         * Si deux appels AJAX sont lancés presque en même temps
         * (par exemple un auto-refresh discret et une recherche utilisateur),
         * il se peut que la première requête (ancienne) termine APRES la seconde.
         * Résultat → l’UI serait écrasée par des données obsolètes.
         *
         * ✅ Solution :
         * On associe un ID unique à chaque requête et
         * on n’applique les résultats QUE si c’est la requête la plus récente.
         */
        this.lastRequestId = 0;
    }

    /**
     * Charge les entités depuis le serveur et met à jour la table ou la liste.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     * @param {Object} filters - Objets contenant les filtres actifs.
     */
    loadEntities(page, filters = {}, discret = false) {


        // Ne pas loadEntities si une cellule est en edition
        if(this.tableUI.cellOrchestrator.active != null || this.tableUI.bulkAction.isSelectingRows  ) return;

        if(page === undefined){
            page = this.tableUI.indexUI.paginationUI.page;
        }
        const pageString = new URLSearchParams({page : page}).toString();
    
        let indexUrl = this.indexUrl;
        indexUrl = this.appendParamsToUrl(
            indexUrl,
            pageString,
        );

        indexUrl = this.appendParamsToUrl(
            indexUrl,
            this.viewStateService.getContextParams()
        );


        // Afficher l'indicateur de chargement
        if (!discret) {
            this.loader.showNomBloquante();
        }

        // 🔹 incrémenter l'id de la requête
        const requestId = ++this.lastRequestId;

        // Requête AJAX pour charger les données
        return $.get(indexUrl)
            .done((html) => {

                /**
                 * 🛑 Problème possible :
                 * Cette réponse correspond peut-être à une requête plus ancienne
                 * (envoyée avant mais arrivée après).
                 *
                 * ✅ Solution :
                 * Vérifier que cette réponse correspond bien à la dernière requête envoyée.
                 * Sinon, on l’ignore pour éviter d’écraser l’UI avec des données périmées.
                 */
                if (requestId !== this.lastRequestId) {
                    console.debug("⚠️ Réponse ignorée (requête obsolète)", { requestId, last: this.lastRequestId });
                    return;
                }

               

                // TODO : à mettre dans this.config
                const view_type = this.config.viewStateService.getVariable(this.config.view_type_variable) || "table";
                if(view_type == "widgets"){
                    $(this.config.dataContainerSelector).html("");
                    $(this.config.dataContainerOutSelector).html(html);
                }else{
                    $(this.config.dataContainerSelector).html(html);
                    $(this.config.dataContainerOutSelector).html("");
                }
            
                if (!discret) {
                    this.loader.hide();
                }
                this.executeScripts(html);

                
                this.tableUI.init();
                if (!discret) {
                    this.tableUI.indexUI.filterUI.init();
                }

            

                this.tableUI.indexUI.bulkActionsUI.init();
                this.tableUI.indexUI.notificationUI.init();
                // Afficher un message de succès (optionnel)
                // NotificationHandler.showSuccess('Données chargées avec succès.');

                if(this.config.data_calcul && this.config.isMany && this.config.managerInstance.parent_manager){
                    this.config.managerInstance.parent_manager.formUI.reloadData();
                }
              
                
                    
            })
            .fail((xhr) => {
                AjaxErrorHandler.handleError(xhr, "Erreur lors du chargement des données.");
            })
            .always(() => {
                // Masquer l'indicateur de chargement
               
            });
    }
    
}
