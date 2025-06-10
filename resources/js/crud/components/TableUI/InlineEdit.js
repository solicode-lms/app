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
        this.entityEditor = tableUI.entityEditor;
        this.loader = tableUI.loader;       // Utilisation correcte du loader
        this.activeCell = null;             // Cellule actuellement en édition
        this._eventsBound = false;          // Pour n'attacher les handlers qu'une fois
    }

    /**
     * Initialise les événements nécessaires pour l'édition inline.
     */
    init() {
        if (this._eventsBound) return;
        this._eventsBound = true;
        this._bindInlineEditEvents();
    }

    /**
     * Attache les clics et le survol sur les cellules éditables,
     * ainsi que le clic global pour sortir de l'édition.
     */
    _bindInlineEditEvents() {
        const selector = `${this.config.tableSelector} .editable-cell`;

        // 1) Clic sur une cellule éditable
        EventUtil.bindEvent('click', selector, e => {
            const $cell = $(e.currentTarget);
            e.preventDefault();
            // Si une autre cellule est en cours d'édition, on la valide
            if (this.activeCell && !this.activeCell.is($cell)) {
                this._submitActiveCell();
            }
            // Démarre ou ré-ouvre l'édition de la cellule cliquée
            this._openEditor($cell);
        });

        // 2) Clic en-dehors pour valider l'édition en cours
        EventUtil.bindEvent('click', 'body', e => {
            if (!this.activeCell) return;
            const $target = $(e.target);
            // si on clique ni dans activeCell ni dans son formulaire, on soumet
            if (!$target.closest(this.activeCell).length &&
                !$target.closest(`${this.config.tableSelector}`).length) {
                this._submitActiveCell();
            }
        });

        // 3) Curseur et surlignage au survol
        EventUtil.bindEvent('mouseenter', selector, e => {
            $(e.currentTarget).css({ cursor: 'pointer', backgroundColor: '#e9ecef' });
        });
        EventUtil.bindEvent('mouseleave', selector, e => {
            $(e.currentTarget).css({ cursor: '', backgroundColor: '' });
        });
    }

    /**
     * Ouvre l'éditeur inline pour la cellule donnée.
     * @param {jQuery} $cell 
     */
    async _openEditor($cell) {
        // déjà en édition ?
        if ($cell.data('original') !== undefined) return;

        const field = $cell.data('field');
        const id    = $cell.data('id');
        if (!field || !id) return;

        // annule l'édition précédente
        this._cancelEdit();

        this.activeCell = $cell;
        const rowId = $cell.attr('id') || $cell.closest('tr').attr('id');
        const formUI = new FormUI(this.config, this.tableUI.indexUI, `#${rowId}`);

        const url = this.config.editUrl.replace(':id', id);
        try {
            this.loader.show();
            const response = await $.get(url);
            this.loader.hide();

            const $html = $('<div>').html(response);
            this.executeScripts($html);
            const $formGroup = $html.find(`[name="${field}"]`).closest('.form-group');
            if (!$formGroup.length) {
                console.warn(`Champ '${field}' introuvable.`);
                this.activeCell = null;
                return;
            }

            // garde l'ancien contenu
            $cell.data('original', $cell.html());
            $cell.empty().append($formGroup.contents());
            $formGroup.find('label').hide();

            // init formUI pour ce champ seulement
            formUI.init(() => {}, false);

            const $input = $cell.find(`[name="${field}"]`);
            $input.focus();

            // soumission sur Enter / Escape
            $input.on('keydown.inlineEdit', evt => {
                if (evt.key === 'Escape') this._cancelEdit();
                if (evt.key === 'Enter') this._submitActiveCell();
            });

            // change auto pour select et checkbox
            if ($input.is('select') || $input.is(':checkbox')) {
                $input.on('change.inlineEdit', () => this._submitActiveCell());
            }

        } catch (err) {
            this.loader.hide();
            console.error('Erreur chargement form inline:', err);
            NotificationHandler.showError("Impossible d'ouvrir l'éditeur inline.");
            this.activeCell = null;
        }
    }

    /**
     * Valide et soumet la mise à jour de la cellule active.
     */
    _submitActiveCell() {
        if (!this.activeCell) return;

        const $cell = this.activeCell;
        const field = $cell.data('field');
        const id    = $cell.data('id');
        const $input = $cell.find('input, select');

        const rowId = $cell.attr('id') || $cell.closest('tr').attr('id');
        const formUI = new FormUI(this.config, this.tableUI.indexUI, `#${rowId}`);

        // validation du formulaire
        if (!formUI.validateForm()) {
            this._cancelEdit();
            return;
        }

        const data = { id, [field]: $input.val() };
        this.entityEditor.update_attributes(data, () => {
            NotificationHandler.showSuccess('Champ mis à jour avec succès.');
            // recharge uniquement la liste
            this.tableUI.entityLoader.loadEntities().done(() => {
                this._cancelEdit(); // remet l'état à zéro
            });
        });

        // on retire la cellule active pour éviter double-soumission
        this.activeCell = null;
    }

    /**
     * Annule l'édition de la cellule courante et restaure son contenu.
     */
    _cancelEdit() {
        if (!this.activeCell) return;
        const $cell = this.activeCell;
        const original = $cell.data('original');
        if (original !== undefined) {
            $cell.off('.inlineEdit');     // retire tous les events spécifiques
            $cell.empty().html(original);
            $cell.removeData('original');
        }
        this.activeCell = null;
    }
}
