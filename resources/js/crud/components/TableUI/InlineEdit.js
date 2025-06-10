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
        EventUtil.bindEvent('click', selector, (e) => this.handleInlineEdit(e));

        // 🔹 Ajouter un curseur pointer + surlignage léger au survol
        EventUtil.bindEvent('mouseover', `${this.config.tableSelector} .editable-cell`, (e) => {
            $(e.currentTarget).css({
                cursor: 'pointer',
                backgroundColor: '#e9ecef',
              
            });
        });

        EventUtil.bindEvent('mouseout', `${this.config.tableSelector} .editable-cell`, (e) => {
            $(e.currentTarget).css({
                cursor: '',
                
                backgroundColor: ''
            });
        });


    }

    /**
     * Gère le processus de remplacement d'une cellule par un champ éditable.
     * @param {Event} e - L'événement déclenché par le double-clic.
     */
    async handleInlineEdit(e) {


       
      


        const $cell = $(e.currentTarget);

        // si on est déjà en édition (original est stocké), on sort
        if ($cell.data('original') !== undefined) {
            return;
        }



        const field = $cell.data('field');
        const id = $cell.data('id');

        // 🛑 Annuler toutes les autres éditions avant de continuer
        this.cancelAllEdits();

        const formUI = new FormUI(this.config, this.tableUI.indexUI, `#${$cell.attr("id") || $cell.closest('tr').attr('id')}`);

        if (!field || !id) return;

        const url = this.config.editUrl.replace(':id', id);

        // try {

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

            const currentValue = $cell.html();
            $cell.data('original', currentValue);
            $cell.empty().append(formField.contents());

            formUI.init(() => {}, false); // Initialiser uniquement le champ ciblé

            const input = $cell.find(`[name="${field}"]`);
            input.focus();

            // Initialisation spécifique pour select2 si nécessaire
            // && input.hasClass('select2')
            if (input.is('select') ) {

                // 🔁 Submit immédiat après changement de valeur
                input.off('change').on('change', () => {
                    this.submit(formUI, input, $cell, field, id);
                });
            } else if (input.is(':checkbox')) {
                input.off('change').on('change', () => {
                    const isChecked = input.is(':checked');
                    input.val(isChecked ? 1 : 0); // for consistency with backend
                    this.submit(formUI, input, $cell, field, id);
                });
            
            }  else {
                input.off('blur').on('blur', () => {
                    this.submit(formUI, input, $cell, field, id);
                });
               
        
              
            }
            this.bindFieldEvents(formUI, input, $cell, field, id);
        // } catch (error) {
        //     console.error('Erreur de chargement du formulaire :', error);
        //     NotificationHandler.showError("Erreur lors de l'ouverture de l'éditeur inline.");
        // }
    }

    /**
     * Attache les événements blur et keydown au champ actif.
     */
    bindFieldEvents(formUI, input, $cell, field, id) {
        
        input.off('keydown').on('keydown', (evt) => {
            if (evt.key === 'Escape') {
              
                this.cancelAllEdits(); // Annuler tout
                
            } else if (evt.key === 'Enter') {
                this.submit(formUI, input, $cell, field, id);
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
                NotificationHandler.showSuccess('Champ mis à jour avec succès.');
                this.tableUI.entityLoader.loadEntities(); // 🔄 Recharger toute la table
            });
        }
    }

    cancelAllEdits() {
        $(`${this.config.tableSelector} .editable-cell`).each(function () {
            const $cell = $(this);
            const original = $cell.data('original');
            if (original !== undefined) {
               
                $cell.empty().html(original); 
                $cell.removeData('original');
            }
        });
    }
}
