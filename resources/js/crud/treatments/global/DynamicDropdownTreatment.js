import { AjaxErrorHandler } from "../../components/AjaxErrorHandler";
import { LoadingIndicator } from "../../components/LoadingIndicator";
import EventUtil from "../../utils/EventUtil";

export default class DynamicDropdownTreatment {
    /**
     * Initialise la gestion du dropdown dynamique.
     */
    constructor(triggerElement) {
        this.triggerElement = triggerElement;
        this.targetSelector = triggerElement.dataset.targetDynamicDropdown;
        this.apiUrlTemplate = triggerElement.dataset.targetDynamicDropdownApiUrl;

        if (!this.targetSelector || !this.apiUrlTemplate) {
            console.warn("Attributs data-target ou data-api-url manquants pour DynamicDropdownTreatment.");
            return;
        }

        this.targetElement = document.querySelector(this.targetSelector);
        this.loader = new LoadingIndicator(this.targetSelector);

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
    }

    /**
     * Met à jour dynamiquement les options du champ cible.
     * @param {string} selectedValue - Valeur sélectionnée dans le champ déclencheur.
     */
    async updateTargetDropdown(selectedValue) {
        const apiUrl = this.apiUrlTemplate.replace("__ID__", selectedValue);
        const previousSelection = this.targetElement.value;

        try {
            this.loader.show(); // Affichage du loader
            const response = await fetch(apiUrl);

            if (!response.ok) {
                throw new Error("Erreur lors du chargement des données.");
            }

            const data = await response.json();
            this.populateDropdown(data, previousSelection);
        } catch (error) {
            AjaxErrorHandler.handleError(error, "Impossible de charger les options.");
        } finally {
            this.loader.hide();
        }
    }

    /**
     * Remplit le champ cible avec les nouvelles options.
     * @param {Array} data - Liste des options récupérées via l'API.
     * @param {string} previousSelection - Ancienne valeur sélectionnée.
     */
    populateDropdown(data, previousSelection) {
        this.targetElement.innerHTML = ""; // Vider les options existantes

        data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.titre;
            this.targetElement.appendChild(option);
        });

        // Restaurer la sélection précédente si encore valide
        if ([...this.targetElement.options].some((opt) => opt.value == previousSelection)) {
            this.targetElement.value = previousSelection;
        }
    }
}
