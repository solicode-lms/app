import { LoadingIndicator } from "./LoadingIndicator";
import { ViewStateService } from './ViewStateService';
import DynamicFieldVisibilityTreatment from "../treatments/form/DynamicFieldVisibilityTreatment";
import { CodeJar } from 'codejar';
import EventUtil from './../utils/EventUtil';
// import { withLineNumbers } from 'codejar/linenumbers';

import Prism from 'prismjs';
import 'prismjs/themes/prism.css'; 
import 'prismjs/components/prism-json';
import InitUIManagers from "../InitUIManagers";

// Import Flatpickr
import flatpickr from 'flatpickr';
// Import the French locale
import { French } from 'flatpickr/dist/l10n/fr.js';
import { DataCalculTreatment } from "../treatments/form/DataCalculTreatment";
import { NotificationHandler } from "./NotificationHandler";
import DynamicDropdownTreatment from "../treatments/global/DynamicDropdownTreatment";
import LocalStorageDefaultTreatment from "../treatments/form/LocalStorageDefaultTreatment";
import SummernoteCodeManager from "./lib/SummernoteCodeManager";
 

export class FormUI  {
    /**
     * Constructeur de la classe FormUI.
     * @param {string} formSelector - Sélecteur CSS du formulaire à gérer.
     * @param {ModalUI} modalUI - Instance de ModalUI pour gérer les interactions modales.
     */
    constructor(config, indexUI , formSelector ="") {
        this.config = config;
        this.indexUI = indexUI;
        this.formSelector = formSelector;
        if(this.formSelector  == "") {
            this.formSelector = this.config.formSelector
        }
        
        this.viewStateService = this.config.viewStateService;
        this.loader = new LoadingIndicator(this.formSelector);
        this.dynamicCalculationTreatment = new DataCalculTreatment(config,this);
        this.localStorageDefaultTreatment = new LocalStorageDefaultTreatment(config,this)
        
        // Update HasManyComponsats on reloadData
        this.reloadHasManyComposants = true;
        
    }

    /**
     * Initialise le gestionnaire de formulaire.
     */
    init(submitHandler, isCreate = true) {


        InitUIManagers.init()

        this.handleCancelButton();
        this.handleCardFooter();
        this.handleFormSubmission(submitHandler);
        this.loader.init();
        this.adapterPourContext(isCreate);
        this.initializeSelect2_in_modal();
        FormUI.initializeRichText();
        FormUI.initSelect2Color()
        FormUI.initializeDate();
        FormUI.initCodeJar();
        FormUI.initTooltip();
        // Initialisation de la gestion des calculs dynamiques
        this.dynamicCalculationTreatment.init();
        if(isCreate){
            this.localStorageDefaultTreatment.init();
        }
       

        if(window.dynamicFieldVisibilityTreatments){
            new DynamicFieldVisibilityTreatment(window.dynamicFieldVisibilityTreatments)
            .initialize();
        }

         // DynamicDropdownTreatment
         document.querySelectorAll(this.formSelector +  " [data-target-dynamic-dropdown]").forEach((element) => {
            new DynamicDropdownTreatment(element,this.config);
        });



       // ——————— Clamp automatique des inputs number[max] avec EventUtil ———————
        EventUtil.bindEvent(
        'input',
        `${this.formSelector} input[type="number"][max]`,
        (e) => {
            const el   = e.target;
            const min  = parseFloat(el.min) || 0;
            const max  = parseFloat(el.max);
            let   val  = parseFloat(el.value);

            if (!isNaN(max)) {
            if (val > max) el.value = max;
            else if (val < min) el.value = min;
            } else if (val < min) {
            el.value = min;
            }
        }
        );

    }

    disableRequiredAttributes() {
        const form = document.querySelector(this.formSelector);
        if (!form) return;
    
        form.querySelectorAll('[required]').forEach((el) => {
            el.removeAttribute('required');
        });
    }
    
    /**
         * Masque les éléments <select> dont l'id correspond à une clé dans le contextState.
         */
    adapterPourContext(isCreate) {

        const scopeData = this.config.viewStateService.getScopeFormVariables();
        const formData = this.config.viewStateService.getFormVariables();

        Object.keys(scopeData).forEach((key) => {
            const filterElement = document.querySelector(`${this.formSelector} #${key}`);
            if (filterElement) {
                if (this.config.isDebug) {
                    filterElement.parentElement.style.backgroundColor = 'lightblue'; // Mode debug : surligner
                } else {
                    filterElement.parentElement.style.display = 'none'; // Masquer l'élément du filtre
                }
            }
        });
       
        // Appliquer les valeurs des form : form et scope et masquer si nécessaire
        // Dans le cas de create seulement
        if(isCreate){
            Object.keys(formData).forEach((key) => {
                const filterElement = document.querySelector(`${this.formSelector} #${key}`);
                if (filterElement) {
                        if (filterElement.tagName === "INPUT" || filterElement.tagName === "TEXTAREA") {
                            filterElement.value = formData[key];
                        } else if (filterElement.tagName === "SELECT") {
                            filterElement.value = formData[key];
                            filterElement.dispatchEvent(new Event("change"));
                        }
                }
            });
        }
       
    }






    /**
     * Gère le bouton d'annulation pour fermer le modal.
     */
    handleCancelButton() {
        EventUtil.bindEvent('click', `${this.formSelector} .form-cancel-button`, (e) => {
            e.preventDefault();
            this.indexUI.modalUI.close();
        });
        EventUtil.bindEvent('click', `${this.config.dynamicModalSelector} .form-cancel-button`, (e) => {
            e.preventDefault();
            this.indexUI.modalUI.close();
        });
        
    }

    /**
     * Modifie le style des pieds de formulaire (footers).
     */
        handleCardFooter() {
        // on cible à la fois le formulaire et l'affichage show
        const contexts = `${this.formSelector}`;

        // on trouve les footers et on bascule la classe
        $(contexts)
            .find('.card-footer')
            .removeClass('card-footer')
            .addClass('modal-footer');

        // on enlève simplement la classe card-body
        $(contexts)
            .find('.card-body')
            .removeClass('card-body');
        }
   /**
     * Attache un gestionnaire d'événements pour la soumission du formulaire.
     * @param {Function} submitHandler - Fonction personnalisée pour gérer la soumission.
     */
   handleFormSubmission(submitHandler) {
    $(document).off('submit', this.formSelector); // Supprime tout gestionnaire précédent pour éviter les doublons
    EventUtil.bindEvent('submit', this.formSelector, (e) => {
        e.preventDefault(); // Empêche le rechargement de la page
        submitHandler(); // Appelle la fonction de soumission passée
    });
}

    /**
     * Configure le formulaire pour le mode lecture seule.
     */
    setToReadOnly() {
        const form = $(this.formSelector);
        form.find('input, select, textarea, button').each(function () {
            const element = $(this);
            if (element.is('input') || element.is('textarea')) {
                element.attr('readonly', true);
            } else if (element.is('select')) {
                element.attr('disabled', true);
            } else if (element.is('button')) {
                element.attr('disabled', true);
            }
        });

        form.find('.btn').not('.form-cancel-button').addClass('d-none');
    }

    /**
     * Réinitialise le formulaire à son état par défaut.
     */
    resetForm() {
        const form = $(this.formSelector);
        form.trigger('reset');
        form.find('input, select, textarea, button').each(function () {
            $(this).removeAttr('readonly').removeAttr('disabled');
        });

        form.find('.btn').removeClass('d-none');
    }

    /**
     * Sérialise et valide les données du formulaire avant l'envoi.
     * @returns {Object|null} - Données sérialisées ou null si la validation échoue.
     */
    getFormData() {
        const form = $(this.formSelector);
        const data = form.serializeArray();
        const isValid = this.validateForm(data);
        return isValid ? data : null;
    }


    /**
     * Valide les données du formulaire.
     * @param {Array} formData - Données du formulaire sérialisées.
     * @returns {boolean} - Retourne `true` si les données sont valides, sinon `false`.
     */

    validateForm() {
        const form = $(this.formSelector);
        let isValid = true;
    
        // Remove previous error messages
        form.find('.error-message').remove();

        form.find('[required]').each(function () {
            const field = $(this);
            const value = field.val();
    
            if (field.is(':checkbox')) {
                // Valider une case à cocher
                if (!field.is(':checked')) {
                    field.addClass('is-invalid');

                    // Add an error message if not already added
                    if (!field.next('.error-message').length) {
                        field.after('<span class="error-message text-danger">Ce champ est obligatoire..</span>');
                    }

                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            } else if (field.is('select[multiple]')) {
                // Valider une liste déroulante multiple
                if (!value || value.length === 0) {
                    field.addClass('is-invalid');
                    // Ajouter un message d'erreur sous le champ
                    field.after('<span class="error-message text-danger">Veuillez sélectionner au moins un élément.</span>');
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            } 
            

            else if (field.is('select')) {
                // Validate a single select dropdown
                if (!value || value === 'default') { // Assuming 'default' is your placeholder value
                    field.addClass('is-invalid');
                    if (!field.next('.error-message').length) {
                        field.after('<span class="error-message text-danger">Veuillez sélectionner une option.</span>');
                    }
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }

            }







            else if (field.hasClass('richText')) {
                // Validate rich text areas (e.g., Summernote)
                const richTextContent = field.val().trim(); // Get Summernote content
                const richTextContainer = field.next('.note-editor'); // Get Summernote container
    
                if (!richTextContent || richTextContent === '<br>' || richTextContent === '<p><br></p>') {
                    richTextContainer.addClass('richText_is_invalid');
    
                    // Add an error message if not already added
                    if (!richTextContainer.next('.error-message').length) {
                        richTextContainer.after('<span class="error-message text-danger">Ce champ ne peut pas être vide.</span>');
                    }
    
                    isValid = false;
                } else {
                    richTextContainer.removeClass('richText_is_invalid');
                    richTextContainer.next('.error-message').remove();
                }
            } 
            
            
            else {
                // Valider les autres champs (texte, email, etc.)
                if (typeof value !== 'string' || !value.trim()) {
                    field.addClass('is-invalid');
                    // Add an error message if not already added
                    if (!field.next('.error-message').length) {
                        field.after('<span class="error-message text-danger">Ce champ ne peut pas être vide.</span>');
                    }
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            }
        });
    
        return isValid;
    }



    initializeSelect2_in_modal() {
        

        //  TODO Il faut ajouter  allowClear: true si le champs nullable
        $(`.select2`).each(function () {

            const $el = $(this);
            if (!$el.is('select')) return;

            let placeholder = $(this).data('label') || "Sélectionnez une option"; // Récupérer data-label ou valeur par défaut

            $(this).select2({
                placeholder: placeholder, // Utiliser data-label comme placeholder
                width: '100%',
                allowClear: true,
            });
        });


     
        // Initialise les éléments Select2 avec thème Bootstrap 4
        // $(`.select2bs4`).select2({
        //     theme: 'bootstrap4',
        // });
    }

    static initSelect2Color(){

        function formatColor(option) {
            if (!option.id) {
                return option.text;
            }
            let color = $(option.element).data('color');
            return $('<span class="color-option"><span class="color-box" style="background-color:' + color + ';"></span>' + option.text + '</span>');
        }

        // Initialiser les .select2Color
        $('.select2Color').each(function () {

            const $el = $(this);
            if (!$el.is('select')) return;

            if (!$el.data('initialized')) {
                $el.select2({
                    templateResult: formatColor,
                    templateSelection: formatColor,
                    width: '100%'
                });
                $el.data('initialized', true);
                console.log($el.data('initialized'));
            }
        });

    }
    static initializeSelect2() {
        // Initialise les éléments Select2
        $('.select2').each(function() {

            const $el = $(this);
            if (!$el.is('select')) return;

            let placeholder = $(this).data('label') || "Sélectionnez une option"; // Récupérer data-label ou valeur par défaut

            // TODO Il faut ajouter  allowClear: true si le champs nullable
            $(this).select2({
                placeholder: placeholder, // Utiliser data-label comme placeholder
                width: '100%',
                allowClear: true,
            });
        });
    }
    static initializeRichText() {

        SummernoteCodeManager.initAll('.richText', {
                height: 100,
        });
            
        // $('.richText').each(function () {
        //     const $textarea = $(this);
        //     const isDisabled = $textarea.prop('disabled');

        //     // Initialisation de Summernote
        //     $textarea.summernote({
        //         height: 80,
        //         toolbar: isDisabled ? false : undefined,
        //         airMode: isDisabled ? false : undefined,
        //         callbacks: isDisabled ? {
        //             onInit: function () {
        //                 $textarea
        //                     .siblings('.note-editor')
        //                     .find('.note-editable')
        //                     .attr('contenteditable', false);
        //             }
        //         } : {}
        //     });

        //     // Synchroniser le textarea => Summernote
        //     $textarea.on('input', function () {
        //         $textarea.summernote('code', $textarea.val());
        //     });

        //     // Synchroniser Summernote => textarea
        //     $textarea.on('summernote.change', function (e, contents) {
        //         $textarea.val(contents);
        //     });
        // });


          
    }



    getFormDataArray() {
        const form = $(this.formSelector);
    
        // Sérialiser les données en tableau
        const dataArray = form.serializeArray();
    
        return dataArray;
    }

    static initializeDate(){

        // document.querySelectorAll('.datetimepicker').forEach(input => {
        //     alert( input.value);
        //     if (input.value && input.value.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
        //         // Convertir "2025-04-21 15:20:00" => "2025-04-21 15:20"
        //         // 2025-04-21 15:20:00
        //         input.value = input.value.slice(0, 16);
        //         alert( input.value);
        //     }
        // });

        
         // Apply Flatpickr to any element with the class 'datetimepicker'
         flatpickr('.datetimepicker', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',  // flatpickr comprendra maintenant le S
            time_24hr: true,
            locale: 'fr',
            allowInput: true,
            weekNumbers: true
        });
    }

    static initCodeJar() {
        // Sélectionner tous les éditeurs avec la classe 'code-editor'
        const editors = document.querySelectorAll('.code-editor');

        editors.forEach((editor) => {
            // Trouver l'input caché associé à cet éditeur
            const hiddenInput = editor.nextElementSibling;



            // Fonction de coloration syntaxique
            const highlight = (editorItem) => {
                try {
                    const formattedJSON = JSON.stringify(JSON.parse(editorItem.textContent), null, 2);
                    editorItem.innerHTML = Prism.highlight(formattedJSON, Prism.languages.json, 'json');
                } catch (error) {
                    editorItem.innerHTML = Prism.highlight(editorItem.textContent, Prism.languages.json, 'json');
                }
            };

            // Initialiser CodeJar pour cet éditeur
            const jar = CodeJar(editor, highlight);

            // Synchroniser avec l'input caché lors de chaque mise à jour
            jar.onUpdate((editorData) => {
                try {
                    const formattedJSON = JSON.stringify(JSON.parse(editor.textContent), null, 2);
                    editor.style.borderColor = ''; // Bordure normale si valide
                    hiddenInput.value = formattedJSON; // Mettre à jour l'input caché
                } catch (error) {
                    editor.style.borderColor = 'red'; // Bordure rouge si invalide
                }
            });

           // Ajouter un écouteur d'événement sur le hiddenInput
            hiddenInput.addEventListener('change', () => {
                try {
                    editor.textContent = JSON.stringify(JSON.parse(hiddenInput.value), null, 2);
                    highlight(editor);
                } catch (error) {
                    console.error("Erreur lors de la mise à jour de l'éditeur :", error);
                }
            });

            EventUtil.bindEvent('change', `#${hiddenInput.id}`, () => {
                try {
                    editor.textContent = hiddenInput.value;
                    highlight(editor);
                } catch (error) {
                    NotificationHandler.showError("Mettre à jour JSON : " + error);
                    console.error("Erreur lors de la mise à jour de l'éditeur :", error);
                }
            });


            // Charger le contenu initial, s'il existe
            if (hiddenInput.value) {
                editor.textContent = JSON.stringify(JSON.parse(hiddenInput.value), null, 2);
                highlight(editor);
            }
        });
    }


    /**
     * Affiche les erreurs sous les champs concernés
     * @param {Object} errors - Erreurs renvoyées par le serveur (format Laravel)
     */
    showFieldErrors(errors) {
        const form = $(this.formSelector);
        form.find('.error-message').remove(); // Supprime les anciens messages d'erreur

        Object.entries(errors).forEach(([field, messages]) => {
            let input = form.find(`[name="${field}"]`);
            if (input.length > 0) {
                input.addClass('is-invalid');
                messages.forEach(msg => {
                    input.after(`<div class="error-message text-danger">${msg}</div>`);
                });
            }
        });
    }


    static initTooltip(){


        // Supprimer les tooltip existant avant d'initialiser
        $('.tooltip').remove();

        // // tooltip-inner
        // // Supprimer tous les tooltips existants avant d'en créer de nouveaux
        $('[data-toggle="tooltip"]').tooltip('dispose');
            

        // Supprime tous les tooltips actifs avant de les recréer
        const tooltips = $('[data-toggle="tooltip"]').tooltip({
             placement: "auto"
        });

        tooltips.on('shown.bs.tooltip', function () {
            const $this = $(this);
            setTimeout(() => {
                $this.tooltip('hide'); // Cache le tooltip
            }, 3000); // Temps en ms avant de cacher le tooltip (ici 3 secondes)
        });

        // $('[data-toggle="tooltip"]').tooltip();
      
    }

    /**
     * Recharge les données d'une entité dans le formulaire via une requête AJAX,
     * en utilisant l'ID présent dans le champ `name="id"`.
     *
     * Cette méthode appelle l'URL définie dans `config.getEntityUrl` (ex: `/realisationTaches/json/:id`),
     * remplit automatiquement les champs du formulaire via `showFields()`,
     * et recharge également les composants `hasMany` si la réponse les contient.
     *
     * @returns {void}
     */
    reloadData() {
        const form = document.querySelector(this.formSelector);
        if (!form) return;

        const idInput = form.querySelector('[name="id"]');
        if (!idInput || !idInput.value) {
            NotificationHandler.showError("Aucun ID trouvé pour le rechargement.");
            return;
        }

        const id = idInput.value;
        const url = this.config.getEntityUrl.replace(':id', id); // URL: /realisationTaches/json/:id

        this.loader.showNomBloquante("Mise à jour des données de formulaire");

        // Désactiver temporairement le calcul
        this.dynamicCalculationTreatment.disableCalcul = false;
        this.reloadHasManyComposants = false;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
        })
            .done((response) => {
                // Tu peux enrichir ici si le back ne retourne pas { entity: ..., hasManyInputsToUpdate: ... }
                this.showFields(response, "", {}); // on suppose aucun hasMany à recharger ici
                // NotificationHandler.showSuccess("Formulaire mis à jour avec succès.");
            })
            .fail((xhr) => {
                const message = xhr.responseJSON?.message || "Erreur lors du chargement des données.";
                NotificationHandler.showError(message);
            })
            .always(() => {
                this.loader.hide();

                // Réactiver le calcul après un petit délai (évite les conflits)
                setTimeout(() => {
                    this.dynamicCalculationTreatment.disableCalcul = true;

                    this.reloadHasManyComposants = true;
                }, 200);
            });
    }

    showFields(fields,elseField = "", hasManyInputsToUpdate){
         Object.entries(fields).forEach(([key, value]) => {
         
        // Sélectionner le champ en tenant compte des crochets `[]` pour `select multiple`
        const field = $(`${this.config.formSelector} [name="${key}"], ${this.config.formSelector} [name="${key}[]"]`);

        // Vérifier si c'est le champ qui a déclenché l'événement, éviter de le mettre à jour
        if (field.is(elseField)) {
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
                     field.val(value);

                    // Si c'est un éditeur Summernote (richText)
                    if (field.hasClass('richText') && typeof field.summernote === 'function') {
                        field.summernote('code', value);
                    } else {
                        field.trigger('change');
                    }
                }
            }
        });

         // Mise à jour des composants hasMany
         
        if (
            this.reloadHasManyComposants 
            && hasManyInputsToUpdate 
            && typeof hasManyInputsToUpdate === 'object'
        ) {
            Object.entries(hasManyInputsToUpdate).forEach(([key, managerId]) => {
                const crudManager = window.crudModalManagers?.[managerId];
                if (crudManager) {
                    crudManager.tableUI.loadListAction.loadEntities(); // recharge les entités
                } else {
                  //  console.warn(`⚠️ Composant hasMany non trouvé pour "${managerId}"`);
                }
            });
        }
    }
  

}
