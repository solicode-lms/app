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

        // 🔹 Activer l'édition au double-clic
        $(document).on('dblclick', selector, async (e) => {
            const $cell = $(e.currentTarget);
            const field = $cell.data('field');
            const id = $cell.data('id');

            if (!field || !id) return;

            const url = this.config.editUrl.replace(':id', id);
            try {
                const response = await $.get(url);
                const html = $('<div>').html(response);
                const formField = html.find(`[name="${field}"]`).closest('.form-group');

                if (!formField.length) {
                    console.warn(`Champ '${field}' introuvable dans le formulaire.`);
                    return;
                }

                const $editableZone = $cell;
                $editableZone.find('.editable-text').addClass('d-none');
                $editableZone.find('.editable-input').remove();
                $editableZone.append(formField);

                const input = $editableZone.find(`[name="${field}"]`);
                input.focus();

                // Blur → validation automatique
                input.on('blur', () => {
                    const newValue = input.val();
                    const data = { id, [field]: newValue };
                    this.entityEditor.update_attributes(data, () => {
                        $editableZone.find('.editable-text').text(newValue).removeClass('d-none');
                        formField.remove();
                        NotificationHandler.showSuccess('Champ mis à jour avec succès.');
                    });
                });

                // ENTER → validation
                input.on('keydown', (evt) => {
                    if (evt.key === 'Enter') {
                        input.blur();
                    }
                });
            } catch (error) {
                console.error('Erreur de chargement du formulaire :', error);
                NotificationHandler.showError('Erreur lors de l\'ouverture de l\'éditeur inline.');
            }
        });
    }
}
