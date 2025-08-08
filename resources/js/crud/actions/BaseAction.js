import { ViewStateService } from "../components/ViewStateService";
import { FormUI } from "../components/FormUI";
import { LoadingIndicator } from "../components/LoadingIndicator";
import { ModalUI } from "../components/ModalUI";
import EventUtil from '../utils/EventUtil';
export class BaseAction {


    constructor(config){
        this.config = config;
        // Table Loader
        this.loader = new LoadingIndicator(config.tableSelector);
        this.loader_traitement = new LoadingIndicator("body");
        this.viewStateService = this.config.viewStateService;
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


      /**
     * Exécute les scripts inclus dans le contenu HTML chargé via AJAX.
     * @param {string} html - Contenu HTML contenant potentiellement des scripts.
     */
      executeScripts(html) {
        const scriptTags = $("<div>").html(html).find("script");

        scriptTags.each(function () {
            const scriptText = $(this).text();
            const scriptSrc = $(this).attr("src");

            if (scriptSrc) {
                // Charger et exécuter les scripts externes
                $.getScript(scriptSrc);
            } else if (scriptText) {
                // Exécuter les scripts inline
                new Function(scriptText)();
            }
        });
    }

}