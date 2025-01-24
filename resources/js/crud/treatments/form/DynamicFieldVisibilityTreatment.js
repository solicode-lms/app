export default class DynamicFieldVisibilityTreatment {
    constructor(entitiesArray) {
        // entitiesArray : liste des traitements pour chaque entité
        this.entitiesArray = entitiesArray || [];
        this.initialize();
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
            const $dropdownElement = $(`#${targetDropdownId}`);
            if ($dropdownElement.length === 0) {
                console.warn(`Dropdown avec l'ID "${targetDropdownId}" introuvable.`);
                return;
            }

            // Ajouter un écouteur d'événement pour gérer les changements
            $dropdownElement.on('change', (event) => {
                this.handleVisibility(dataDefinitions, fieldMappings, typeField, $(event.target).val());
            });

            // Initialiser les champs au chargement
            const initialValue = $dropdownElement.val();
            if (initialValue) {
                this.handleVisibility(dataDefinitions, fieldMappings, typeField, initialValue);
            }
        });
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
