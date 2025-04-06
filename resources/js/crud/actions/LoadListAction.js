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
    }

    /**
     * Charge les entités depuis le serveur et met à jour la table ou la liste.
     * @param {number} page - Numéro de la page à charger (par défaut : 1).
     * @param {Object} filters - Objets contenant les filtres actifs.
     */
    loadEntities(page, filters = {}) {

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
        this.loader.show();


        // Requête AJAX pour charger les données
        $.get(indexUrl)
            .done((html) => {
                // TODO : à mettre dans this.config
                const view_type = this.config.viewStateService.getVariable(this.config.view_type_variable) || "table";
                if(view_type == "widgets"){
                    $(this.config.dataContainerSelector).html("");
                    $(this.config.dataContainerOutSelector).html(html);
                }else{
                    $(this.config.dataContainerSelector).html(html);
                    $(this.config.dataContainerOutSelector).html("");
                }
               
                this.executeScripts(html);
                this.tableUI.init();
                this.tableUI.indexUI.filterUI.init();
                // Afficher un message de succès (optionnel)
                // NotificationHandler.showSuccess('Données chargées avec succès.');
            })
            .fail((xhr) => {
                AjaxErrorHandler.handleError(xhr, "Erreur lors du chargement des données.");
            })
            .always(() => {
                // Masquer l'indicateur de chargement
                this.loader.hide();
            });
    }
    
}
