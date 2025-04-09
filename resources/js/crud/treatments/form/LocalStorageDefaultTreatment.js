import EventUtil from './../../utils/EventUtil';

/**
 * Ce traitement permet de mémoriser la valeur d’un champ dans le localStorage
 * et de la restaurer automatiquement au chargement du formulaire.
 * Il fonctionne sur tout champ contenant l’attribut data-store-key.
 */
export default class LocalStorageDefaultTreatment {
    constructor(config, formUI) {
        this.formUI = formUI;
        this.config = config;
        this.formSelector = this.config.formSelector;
    }

    init() {
        const fields = document.querySelectorAll(`${this.formSelector} [data-store-key]`);
    
        fields.forEach(input => {
            const key = input.dataset.storeKey;
    
            // 🟢 Récupérer et appliquer la valeur sauvegardée
            const savedValue = localStorage.getItem(key);
    
            if (input.type === 'checkbox') {
                if (savedValue !== null) {
                    input.checked = savedValue === 'true';
                }
            } else if (savedValue !== null && input.value === '') {
                input.value = savedValue;
            }
    
            // 🔄 Définir le bon type d'événement
            const eventType = ['checkbox', 'radio', 'select-one'].includes(input.type) || input.tagName === 'SELECT'
                ? 'change'
                : 'input';
    
            // 💾 Sauvegarde dans le localStorage
            EventUtil.bindEvent(eventType, input, () => {
                if (input.type === 'checkbox') {
                    localStorage.setItem(key, input.checked.toString());
                } else {
                    localStorage.setItem(key, input.value);
                }
            });
        });
    }
    
}
