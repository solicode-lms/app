import { ContextStateManager } from "../components/ContextStateManager";
import { FormManager } from "../components/FormManager";
import { LoadingIndicator } from "../components/LoadingIndicator";
import { ModalManager } from "../components/ModalManager";

export class BaseAction {


    constructor(config){
        this.config = config;
        this.modalManager = new ModalManager(config.modalSelector);
        // Table Loader
        this.loader = new LoadingIndicator(config.tableSelector);
        this.formManager = new FormManager(this.config, this.modalManager);
        this.contextManager = new ContextStateManager(this.config.contextState);
        this.SuscesMessage = "";
    }
    /**
     * Génère une URL dynamique en remplaçant :id par un identifiant spécifique.
     * @param {string} baseUrl - URL de base.
     * @param {number|string} id - Identifiant à insérer.
     * @returns {string} URL complète avec l'identifiant.
     */
    getUrlWithId(baseUrl, id) {
        return baseUrl.replace(':id', id);
    }

      /**
     * Ajoute des paramètres à une URL.
     * @param {String} url - L'URL de base.
     * @param {String} params - Chaîne de paramètres à ajouter.
     * @returns {String} - L'URL avec les paramètres ajoutés.
     */
      appendParamsToUrl(url, params) {
        if (!params) {
            return url;
        }

        
        const separator = url.includes('?') ? '&' : '?';
        return `${url}${separator}${params}`;
    }

}