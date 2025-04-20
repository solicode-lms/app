import { Action } from "../../actions/Action";
import EventUtil from "../../utils/EventUtil";
import { FormUI } from "../FormUI";
import { NotificationHandler } from "../NotificationHandler";
 
export class InlineEdit extends Action  {
    constructor(config, tableUI) {
        super(config);
        this.config = config;
        this.tableUI = tableUI;
        this.entityEditor = this.tableUI.entityEditor; // Injectée via CrudManager
       
    }

    init() {
        this.bindInlineEditEvents();
    }

    bindInlineEditEvents() {
        const selector = `${this.config.tableSelector} .editable-cell`;
        EventUtil.bindEvent('dblclick', selector, (e) => this.handleInlineEdit(e));
    }

    async handleInlineEdit(e) {
        const $cell = $(e.currentTarget);
        const field = $cell.data('field');
        const id = $cell.data('id');
        const formUI = new FormUI(this.config, this.tableUI.indexUI,`#${$cell.attr("id") || $cell.closest('tr').attr('id')}`)

        if (!field || !id) return;

        const url = this.config.editUrl.replace(':id', id);
        try {

            this.loader.show();
            const response = await $.get(url);
            this.loader.hide();

            const html = $('<div>').html(response);
            this.executeScripts(html);

            const formField = html.find(`[name="${field}"]`).closest('.form-group');

           

            if (!formField.length) {
                console.warn(`Champ '${field}' introuvable dans le formulaire.`);
                return;
            }

            const currentValue = $cell.text().trim();
            $cell.data('original', currentValue);
            $cell.empty().append(formField);

           
            formUI.init(() => {},false);
            

            const input = $cell.find(`[name="${field}"]`);
            input.focus();

            this.bindFieldEvents(formUI, input, $cell, field, id);
        } catch (error) {
            console.error('Erreur de chargement du formulaire :', error);
            NotificationHandler.showError("Erreur lors de l'ouverture de l'éditeur inline.");
        }
    }

    bindFieldEvents(formUI, input, $cell, field, id) {
        input.off('blur').on('blur', () => {
            this.submit(formUI, input, $cell, field, id);
        });
        input.off('keydown').on('keydown', (evt) => {
            if (evt.key === 'Enter') {
                input.blur();
            } else if (evt.key === 'Escape') {
                $cell.empty().text($cell.data('original'));
            }
        });
    }

    submit(formUI, input, $cell, field, id){
        const isValid = formUI.validateForm();
        if(isValid){
            const newValue = input.val();
            const data = { id, [field]: newValue };

            this.entityEditor.update_attributes(data, () => {
                $cell.empty().text(newValue);
                NotificationHandler.showSuccess('Champ mis à jour avec succès.');
                this.tableUI.entityLoader.loadEntities(); // 🔄 Recharger toute la table
            });
        }
    }
}
