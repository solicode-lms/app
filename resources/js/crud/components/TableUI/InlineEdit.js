import { Action } from "../../actions/Action";
import EventUtil from "../../utils/EventUtil";
import { FormUI } from "../FormUI";
import { NotificationHandler } from "../NotificationHandler";

/**
 * Gère l'édition inline d'un champ dans un tableau CRUD.
 */
export class InlineEdit extends Action {
    constructor(config, tableUI) {
        super(config);
        this.config = config;
        this.tableUI = tableUI;
        this.entityEditor = this.tableUI.entityEditor; // Fournie via CrudManager
    }

    /**
     * Initialise les événements nécessaires pour l'édition inline.
     */
    init() {
        this.bindInlineEditEvents();
    }

    /**
     * Attache le double-clic aux cellules éditables pour déclencher l'édition.
     */
    bindInlineEditEvents() {
        const selector = `${this.config.tableSelector} .editable-cell`;
        EventUtil.bindEvent('dblclick', selector, (e) => this.handleInlineEdit(e));
    }

    /**
     * Gère le processus de remplacement d'une cellule par un champ éditable.
     * @param {Event} e - L'événement déclenché par le double-clic.
     */
    async handleInlineEdit(e) {
        const $cell = $(e.currentTarget);
        const field = $cell.data('field');
        const id = $cell.data('id');
        const formUI = new FormUI(this.config, this.tableUI.indexUI, `#${$cell.attr("id") || $cell.closest('tr').attr('id')}`);

        if (!field || !id) return;

        const url = this.config.editUrl.replace(':id', id);

        try {

            // Récupération de formulaire de l'édition avec tous les champs
            this.loader.show();
            const response = await $.get(url);
            this.loader.hide();

            // Trouver le form-groupe du champs
            const html = $('<div>').html(response);
            this.executeScripts(html);
            const formField = html.find(`[name="${field}"]`).closest('.form-group');
            if (!formField.length) {
                console.warn(`Champ '${field}' introuvable dans le formulaire.`);
                return;
            }
            formField.find('label').hide();

            const currentValue = $cell.text().trim();
            $cell.data('original', currentValue);
            $cell.empty().append(formField.contents());

            formUI.init(() => {}, false); // Initialiser uniquement le champ ciblé

            const input = $cell.find(`[name="${field}"]`);
            input.focus();

            this.bindFieldEvents(formUI, input, $cell, field, id);
        } catch (error) {
            console.error('Erreur de chargement du formulaire :', error);
            NotificationHandler.showError("Erreur lors de l'ouverture de l'éditeur inline.");
        }
    }

    /**
     * Attache les événements blur et keydown au champ actif.
     */
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

    /**
     * Valide et soumet la mise à jour du champ édité.
     */
    submit(formUI, input, $cell, field, id) {
        const isValid = formUI.validateForm();
        if (isValid) {
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
