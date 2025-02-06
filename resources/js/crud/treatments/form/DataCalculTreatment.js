

import { AjaxErrorHandler } from "../../components/AjaxErrorHandler";
import { LoadingIndicator } from '../../components/LoadingIndicator';
import EventUtil from '../../utils/EventUtil';

export class DataCalculTreatment {
    /**
     * Constructeur pour initialiser la gestion des calculs dynamiques.
     * @param {string} formSelector - Sélecteur du formulaire à gérer.
     * @param {string} calculationUrl - URL de l’API pour recalculer les valeurs dépendantes.
     */
    constructor(config, formUI) {
        this.formUI = formUI;
        this.config = config;
        this.debounceDelay = 500; // Délai pour limiter les appels AJAX
        this.currentRequest = null; // Stocke la requête en cours
        this.lastSentData = null; // Stocke les dernières données envoyées
        this.loader = new LoadingIndicator(this.config.formSelector);
    }

    /**
     * Initialise la gestion des événements pour détecter les modifications des champs dynamiques.
     */
    init() {
        // Gestion des champs texte, nombre, textarea
        EventUtil.bindEvent('input', `${this.config.formSelector} [data-calcul="true"]:not(select, [type="checkbox"], [type="radio"])`, (e) => {
            this.debouncedCalculate(e.currentTarget);
        });
    
        // Gestion des <select> et des cases à cocher avec "change"
        EventUtil.bindEvent('change', `${this.config.formSelector} [data-calcul="true"]`, (e) => {
            this.debouncedCalculate(e.currentTarget);
        });
    }

    /**
     * Applique un délai avant d'envoyer la requête AJAX pour éviter les requêtes excessives.
     * @param {HTMLElement} field - Champ modifié.
     */
    debouncedCalculate(field) {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => {
            this.triggerCalculation(field);
        }, this.debounceDelay);
    }

    /**
     * Envoie une requête AJAX au serveur pour recalculer les valeurs dépendantes.
     * @param {HTMLElement} field - Champ modifié.
     */
    triggerCalculation(sourceField) {
        const form = $(this.config.formSelector);

         // Supprimer la méthode put en cas de update
        let formData = form.serializeArray();
        formData = formData.filter(field => field.name !== "_method");
        formData = $.param(formData);


        // Vérifier si les données envoyées sont identiques à la dernière requête pour éviter les doublons
        if (this.lastSentData === formData) return;
        this.lastSentData = formData;

        // Annuler la requête précédente si elle est en cours
        if (this.currentRequest !== null) {
             this.currentRequest.abort();
        }

        // Il dirrange l'utilisateur
        this.loader.showNomBloquante();
        this.currentRequest = $.ajax({
            url: this.config.calculationUrl,
            method: 'POST',
            data: formData,
            dataType: 'json',
        })
            .done((response) => {
                // sourceField.prop('disabled', false);
                this.updateDependentFields(sourceField,response.entity);
            })
            .fail((xhr, textStatus) => {
                // Ignorer les erreurs d'annulation de requête AJAX
                if (textStatus === 'abort') {
                    return;
                }
                if (xhr.responseJSON?.errors) {
                    this.formUI.showFieldErrors(xhr.responseJSON.errors);
                } else {
                   
                    AjaxErrorHandler.handleError(xhr, "Erreur lors du recalcul.");
                }
            })
            .always(() => {
                this.loader.hide();
                this.currentRequest = null;
            });
    }

    /**
     * Met à jour les champs dépendants avec les nouvelles valeurs reçues du serveur.
     * @param {Object} response - Données mises à jour reçues du serveur.
     */
    updateDependentFields(sourceField, response) {
        Object.entries(response).forEach(([key, value]) => {
         
             // Sélectionner le champ en tenant compte des crochets `[]` pour `select multiple`
            const field = $(`${this.config.formSelector} [name="${key}"], ${this.config.formSelector} [name="${key}[]"]`);

           // Vérifier si c'est le champ qui a déclenché l'événement, éviter de le mettre à jour
        if (field.is(sourceField)) {
            return;
        }

            if (field.length > 0) {

                // Vérifier si c'est un select multiple
                if (field.is('select[multiple]')) {
                    let values = [];

                    if (Array.isArray(value)) {
                        // Extraire uniquement les IDs si les valeurs sont des objets
                        values = value.map(v => (typeof v === 'object' && v !== null ? v.id : v));
                    }
                    field.val(values).trigger('change');
                }

                // Vérifier si c'est un select2
                else if (!field.is('select[multiple]') && field.is('select')) {
                    field.val(value).trigger('change'); // Mettre à jour et déclencher l'événement
                } 
                // Vérifier si c'est un champ de type checkbox ou radio
                else if (field.is(':checkbox, :radio')) {
                    field.prop('checked', !!value);
                } 
                // Vérifier si c'est un champ input classique ou textarea
                else {
                    field.val(value).trigger('change');
                }
            }
        });
    }
    
}
