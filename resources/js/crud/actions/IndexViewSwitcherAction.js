import { Action } from './Action';
import EventUtil from '../utils/EventUtil';
import { NotificationHandler } from '../components/NotificationHandler';

export class IndexViewSwitcherAction extends Action {
    /**
     * @param {Object} config - Configuration CRUD globale
     * @param {Object} tableUI - L'instance de TableUI
     */
    constructor(config, tableUI) {
        super(config);
        this.config = config;
        this.tableUI = tableUI;
        this.viewState = config.viewStateService;
        this.view_type_variable = this.config.view_type_variable
    }

    init() {
        this.handleViewSwitchClick();
        this.highlightActiveView();
    }

    handleViewSwitchClick() {
        EventUtil.bindEvent('click', '.view-switch-option', (e) => {
            e.preventDefault();
            const selectedType = $(e.currentTarget).data('view-type');

            const view_type_variable = `${this.view_type_variable}`
            this.viewState.setVariable(view_type_variable,selectedType)
            this.highlightActiveView(selectedType);
            this.tableUI.entityLoader.loadEntities(1); // Recharge la liste
            this.tableUI.indexUI.filterUI.init();
        });
    }

    /**
     * Active le bouton visuellement dans le dropdown
     * @param {String} selectedType
     */
    highlightActiveView(selectedType = null) {
        const current = selectedType || this.viewState.getVariable(this.view_type_variable) || `${this.config.entity_name}_table`;
        $('.view-switch-option').removeClass('active');
        $(`.view-switch-option[data-view-type="${current}"]`).addClass('active');
    }
}
