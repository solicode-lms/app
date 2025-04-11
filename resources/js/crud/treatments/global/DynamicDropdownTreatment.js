import { AjaxErrorHandler } from "../../components/AjaxErrorHandler";
import { LoadingIndicator } from "../../components/LoadingIndicator";
import EventUtil from "../../utils/EventUtil";

// TODO : Le cas de update : tiggerElementpeut avoir une valeur
export default class DynamicDropdownTreatment {
    /**
     * Initialise la gestion du dropdown dynamique.
     */
    constructor(triggerElement, config) {
        this.config = config;
        this.triggerElement = triggerElement;
        this.targetSelector = triggerElement.dataset.targetDynamicDropdown;
        this.apiUrlTemplate = triggerElement.dataset.targetDynamicDropdownApiUrl;
        this.targetDynamicDropdownFilter = triggerElement.dataset.targetDynamicDropdownFilter;

        let containerSelector = '#card_crud';
        if (this.config.formSelector && document.querySelector(this.config.formSelector)) {
            containerSelector = this.config.formSelector;
        } else if (this.config.filterFormSelector && document.querySelector(this.config.filterFormSelector)) {
            containerSelector = this.config.filterFormSelector;
        }
        this.loader = new LoadingIndicator(containerSelector);


        if (!this.targetSelector || !this.apiUrlTemplate) {
            console.warn("Attributs data-target ou data-api-url manquants pour DynamicDropdownTreatment.");
            return;
        }

        this.targetElement = document.querySelector(this.targetSelector);

     

        if (!this.targetElement) {
            console.warn(`Élément cible "${this.targetSelector}" introuvable.`);
            return;
        }

        this.init();
    }

    /**
     * Initialise l'écouteur d'événement sur le champ déclencheur.
     */
    init() {
        let selector = `[name='${this.triggerElement.name}']`;
        EventUtil.bindEvent("change", selector, async (event) => {
            await this.updateTargetDropdown(event.target.value);
        });

        // Cas de mise à jour : si le champ déclencheur a une valeur initiale, 
        // id target element est vide : charger les options
        if (this.triggerElement.value) {
 
             this.updateTargetDropdown(this.triggerElement.value);
        }

    }

    /**
     * Met à jour dynamiquement les options du champ cible.
     * @param {string} selectedValue - Valeur sélectionnée dans le champ déclencheur.
     */
    async updateTargetDropdown(selectedValue) {

        if(!selectedValue){ return; }

        // Vérifier si la valeur est déjà chargée
        const previouslyLoadedFor = this.targetElement.getAttribute("data-loaded-for");
        if (previouslyLoadedFor === selectedValue) {
            return; // Ne rien faire si les données sont déjà chargées
        }
        const apiUrl = `${this.apiUrlTemplate}?filter=${this.targetDynamicDropdownFilter}&value=${selectedValue}`;
        const previousSelection = this.targetElement.value;
        this.targetElement.value = "";


        this.loader.showNomBloquante();
        $.get(apiUrl)
        .done((html) => {
            // Injecter le contenu du formulaire dans le modal
          
            const data =  html;
            this.populateDropdown(data, previousSelection);

            // 🔄 Marquer comme chargée pour cette valeur
            this.targetElement.setAttribute("data-loaded-for", selectedValue);
        })
        .fail((xhr) => {
            AjaxErrorHandler.handleError(xhr, 'Impossible de charger les options.');
        })
        .always(() => {
            this.loader.hide();
        });

        // try {
        
        //     const response = await fetch(apiUrl);

        //     if (!response.ok) {
        //         throw new Error("Erreur lors du chargement des données.");
        //     }

        //     const data = await response.json();
        //     this.populateDropdown(data, previousSelection);
        // } catch (error) {
        //     AjaxErrorHandler.handleError(error, "Impossible de charger les options.");
        // } finally {
        
        // }
    }

    /**
     * Remplit le champ cible avec les nouvelles options.
     * @param {Array} data - Liste des options récupérées via l'API.
     * @param {string} previousSelection - Ancienne valeur sélectionnée.
     */
    populateDropdown(data, previousSelection) {
        // Conserver l'option vide existante s'il y en a une
        let emptyOption = this.targetElement.querySelector('option[value=""]');
        if (!emptyOption) {
            emptyOption = document.createElement("option");
            emptyOption.value = "";
            emptyOption.textContent = this.targetElement.options.length > 0 ? this.targetElement.options[0].textContent : ""; // Utilise le label existant
        }
    
       this.targetElement.innerHTML = ""; // Vider les options existantes
       this.targetElement.appendChild(emptyOption); // Ajouter l'option vide en premier
    
        data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.toString;
            this.targetElement.appendChild(option);
        });
    
        // Restaurer la sélection précédente si encore valide, sinon sélectionner l'option vide
        if ([...this.targetElement.options].some((opt) => opt.value == previousSelection)) {
            this.targetElement.value = previousSelection;
        } else {
            this.targetElement.value = "";
        }
    }
    
}
