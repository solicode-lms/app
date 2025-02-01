import { ContextStateService } from "../components/ContextStateService";
import { FormUI } from "../components/FormUI";
import { LoadingIndicator } from "../components/LoadingIndicator";
import { ModalUI } from "../components/ModalUI";

export class BaseAction {


    constructor(config){
        this.config = config;
        // Table Loader
        this.loader = new LoadingIndicator(config.tableSelector);
        this.contextService = this.config.contextStateService;
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