import EventUtil from './../../utils/EventUtil';

export default class DynamicFieldVisibilityTreatment {
    constructor(entitiesArray) {
        // entitiesArray : liste des traitements pour chaque entité
        this.entitiesArray = entitiesArray || [];
    }

    initialize() {
        if (!Array.isArray(this.entitiesArray) || this.entitiesArray.length === 0) {
            console.warn('Aucune configuration valide trouvée pour DynamicFieldVisibilityTreatment.');
            return;
        }
    
        // Parcourir toutes les configurations
        this.entitiesArray.forEach(entity => {
            const { targetDropdownId, dataDefinitions, fieldMappings, typeField } = entity;
    
            if (!dataDefinitions || !targetDropdownId || !fieldMappings || !typeField) {
                console.warn('Configuration incomplète pour DynamicFieldVisibilityTreatment:', entity);
                return;
            }
    
            // Trouver le dropdown correspondant
            const dropdownElement = document.getElementById(targetDropdownId);
            if (!dropdownElement) {
                console.warn(`Dropdown avec l'ID "${targetDropdownId}" introuvable.`);
                return;
            }
    
            // Initialiser la visibilité avec une valeur vide
            this.handleVisibility(dataDefinitions, fieldMappings, typeField, "");
    
           
            EventUtil.bindEvent('change', `#${targetDropdownId}`, (e) => {
                e.preventDefault();
                this.handleVisibility(dataDefinitions, fieldMappings, typeField, e.target.value);
            });


           // dropdownElement.dispatchEvent(new Event("change"));
           // console.log(dropdownElement);


            // Initialiser les champs avec la valeur actuelle du dropdown (si elle existe)
            const initialValue =  $(`#${targetDropdownId}`).select2("val");
            if (initialValue) {
                this.handleVisibility(dataDefinitions, fieldMappings, typeField, initialValue);
            }
        });
    }
    
    listEventListeners(element) {
        const events = Object.keys(element)
            .filter(key => key.startsWith("on"))
            .map(key => key.slice(2));
    
        console.log(`Événements supportés par`, element, `:`, events);
    }
    

    handleVisibility(dataDefinitions, fieldMappings, typeField, selectedValue) {
        // Réinitialiser tous les champs à "masqué"
        fieldMappings.forEach(({ fieldId }) => {
            const $fieldElement = $(`#${fieldId}`);
            if ($fieldElement.length > 0) {
                $fieldElement.closest('.form-group').hide();
            }
        });

        // Trouver la définition correspondante
        const dataDefinition = dataDefinitions.find(def => def.id == selectedValue);

        if (dataDefinition) {
            // Afficher les champs dynamiquement selon les valeurs définies
            fieldMappings.forEach(({ type, fieldId }) => {
                if (dataDefinition[typeField] === type) {
                    this.toggleFieldVisibility(fieldId, true);
                }
            });
        }
    }

    toggleFieldVisibility(fieldId, show) {
        const $fieldElement = $(`#${fieldId}`);
        if ($fieldElement.length > 0) {
            $fieldElement.closest('.form-group').toggle(show);
        }
    }
}
