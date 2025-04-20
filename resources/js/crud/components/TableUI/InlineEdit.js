import { NotificationHandler } from "../NotificationHandler";

export class InlineEdit {
    constructor(config, tableUI) {
        this.config = config;
        this.tableUI = tableUI;
        this.entityEditor = this.tableUI.entityEditor; // Injectée via CrudManager
    }

    init() {
        this.inlineEditEventHandler();
    }

    inlineEditEventHandler() {
        const selector = `${this.config.tableSelector} .editable-cell`;

        // 🔹 Activer l'édition au clic
        $(document).on('dblclick', selector, function () {
            const $cell = $(this);
            $cell.find('.editable-text').addClass('d-none');
            $cell.find('.editable-input').removeClass('d-none').focus();
        });

        // 🔹 Valider au blur
        $(document).on('blur', `${selector} .editable-input`, (e) => {
            this.submitEdit($(e.target).closest('.editable-cell'));
        });

        // 🔹 Valider au ENTER
        $(document).on('keydown', `${selector} .editable-input`, (e) => {
            if (e.key === 'Enter') {
                this.submitEdit($(e.target).closest('.editable-cell'));
            }
        });
    }

    submitEdit($cell) {
        const id = $cell.data('id');
        const field = $cell.data('field');
        const newValue = $cell.find('.editable-input').val();

        if (!id || !field) return;

        const data = {
            id: id,
            [field]: newValue
        };

        this.entityEditor.update_attributes(data, () => {
            $cell.find('.editable-text').text(newValue).removeClass('d-none');
            $cell.find('.editable-input').addClass('d-none');
            NotificationHandler.showSuccess('Champ mis à jour avec succès.');
        });
    }
}
