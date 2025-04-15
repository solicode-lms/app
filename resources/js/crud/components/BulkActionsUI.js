import EventUtil from './../utils/EventUtil';

/**
 * Gère les actions en masse (bulk) sur une table CRUD.
 */
export class BulkActionsUI {
    /**
     * @param {Object} config - Configuration générale.
     * @param {Object} indexUI - Référence à l'interface principale.
     */
    constructor(config, indexUI) {
        this.config = config;
        this.indexUI = indexUI;
    }

    /**
     * Initialise le composant des actions en masse.
     */
    init() {
        this.config.init();
        this.bindCheckAllHandler();
        this.bindCheckRowHandler();
        this.updateCheckAllState();
    }

    /**
     * Met à jour l'état de la case "tout sélectionner" et la barre d'actions.
     */
    updateCheckAllState() {
        const checkboxes = document.querySelectorAll(`${this.config.crudSelector} .check-row`);
        const checkAll = document.querySelector(`${this.config.crudSelector} .check-all-rows`);
        const checkedCount = document.querySelectorAll(`${this.config.crudSelector} .check-row:checked`).length;

        checkAll.checked = (checkedCount === checkboxes.length);
        checkAll.indeterminate = (checkedCount > 0 && checkedCount < checkboxes.length);

        const bulkActionBar = document.querySelector(`${this.config.crudSelector} .crud-bulk-action`);
        const countDisplay = document.querySelector(`${this.config.crudSelector} .bulk-selected-count`);

        if (bulkActionBar && countDisplay) {
            if (checkedCount > 0) {
                bulkActionBar.classList.remove('d-none');

                // Afficher la bar de filtre car, peut être hide()
                const $contentToToggle= $(`${this.config.crudSelector} .card-header:not(:first)`);
                $contentToToggle.show();

                      
                countDisplay.textContent = checkedCount;
            } else {

          
                bulkActionBar.classList.add('d-none');

                // Revenire à l'état initial de bar filtre
                this.indexUI.filterUI.initFilterToogle();

                countDisplay.textContent = 0;
            }
        }
    }

    /**
     * Gère l'événement de "tout sélectionner".
     */
    bindCheckAllHandler() {
        EventUtil.bindEvent('change', `${this.config.crudSelector} .check-all-rows`, (e) => {
            const isChecked = e.currentTarget.checked;
            const checkboxes = document.querySelectorAll(`${this.config.crudSelector} .check-row`);
            checkboxes.forEach(cb => cb.checked = isChecked);
            this.updateCheckAllState();
        });
    }

    /**
     * Gère l'événement de sélection d'une ligne.
     */
    bindCheckRowHandler() {
        EventUtil.bindEvent('change', `${this.config.crudSelector} .check-row`, () => {
            this.updateCheckAllState();
        });
    }
}
