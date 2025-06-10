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
        this.activeCell = null;
       
    }

    /**
     * Initialise les événements et gère un éventuel clic en attente.
     */
    init() {
        this._bindInlineEditEvents();
        this._openPendingClick();
    }

    /**
     * Attache les clics et le survol sur les cellules éditables,
     * ainsi que le clic global pour sortir de l'édition.
     */
    _bindInlineEditEvents() {
        const selector = `${this.config.tableSelector} .editable-cell`;

        // Clic sur cellule
        EventUtil.bindEvent('click', selector, e => {
            const $cell = $(e.currentTarget);
            e.preventDefault();

            // si une autre cellule est en édition
            if (this.activeCell && !this.activeCell.is($cell)) {
                // enregistrer le clic en attente
                const pending = { field: $cell.data('field'), id: $cell.data('id') };
                localStorage.setItem('inlineEditPending', JSON.stringify(pending));
                // valider la cellule active (restart init + reload)
                this._submitActiveCell();
                return;
            }

            // sinon ouvrir l'éditeur
            this._openEditor($cell);
        });

        // Clic en dehors
        EventUtil.bindEvent('click', 'body', e => {
            if (!this.activeCell) return;
            const $t = $(e.target);
            if (!$t.closest(this.activeCell).length && !$t.closest(this.config.tableSelector).length) {
                this._submitActiveCell();
            }
        });

        // survol
        EventUtil.bindEvent('mouseenter', selector, e => {
            $(e.currentTarget).css({ cursor: 'pointer', backgroundColor: '#e9ecef' });
        });
        EventUtil.bindEvent('mouseleave', selector, e => {
            $(e.currentTarget).css({ cursor: '', backgroundColor: '' });
        });
    }

    /**
     * Ouvre l'éditeur inline pour la cellule donnée.
     */
    async _openEditor($cell) {
        if ($cell.data('original') !== undefined) return;
        const field = $cell.data('field');
        const id    = $cell.data('id');
        if (!field || !id) return;

        this._cancelEdit();
        this.activeCell = $cell;
        const rowId = $cell.attr('id') || $cell.closest('tr').attr('id');
        const formUI = new FormUI(this.config, this.tableUI.indexUI, `#${rowId}`);
        const url = this.config.editUrl.replace(':id', id);

        try {
            this.loader.show();
            const resp = await $.get(url);
            this.loader.hide();

            const $html = $('<div>').html(resp);
            this.executeScripts($html);
            const $grp = $html.find(`[name="${field}"]`).closest('.form-group');
            if (!$grp.length) { console.warn(`Champ '${field}' introuvable.`); this.activeCell = null; return; }

            $cell.data('original', $cell.html()).empty().append($grp.contents());
            $grp.find('label').hide();
            formUI.init(() => {}, false);
            const $input = $cell.find(`[name="${field}"]`);
            $input.focus();

            // key events
            $input.on('keydown.inlineEdit', evt => {
                if (evt.key === 'Escape') this._cancelEdit();
                if (evt.key === 'Enter') this._submitActiveCell();
            });
            // change auto
            if ($input.is('select') || $input.is(':checkbox')) {
                $input.on('change.inlineEdit', () => this._submitActiveCell());
            }
        } catch (err) {
            this.loader.hide();
            console.error('Erreur chargement inline:', err);
            NotificationHandler.showError("Erreur lors de l'ouverture de l'éditeur inline.");
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
        if (!formUI.validateForm()) { this._cancelEdit(); return; }

        const payload = { id, [field]: $input.val() };
        this.entityEditor.update_attributes(payload, () => {
            NotificationHandler.showSuccess('Champ mis à jour.');
            this.tableUI.entityLoader.loadEntities().done(() => {
                // après reload, on ré-init pour gérer le pending
               // this.init();
            });
        });
        this.activeCell = null;
    }

    /**
     * Annule l'édition courante et restaure la cellule.
     */
    _cancelEdit() {
        if (!this.activeCell) return;
        const $cell = this.activeCell;
        const orig = $cell.data('original');
        if (orig !== undefined) {
            $cell.off('.inlineEdit').empty().html(orig);
            $cell.removeData('original');
        }
        this.activeCell = null;
    }

    /**
     * Ouvre l'éditeur sur le clic stocké en attente.
     */
    _openPendingClick() {
        const raw = localStorage.getItem('inlineEditPending');
        if (!raw) return;
        let pending;
        try { pending = JSON.parse(raw); } catch { return; }
        localStorage.removeItem('inlineEditPending');

        const selector = `${this.config.tableSelector} .editable-cell[data-field=\"${pending.field}\"][data-id=\"${pending.id}\"]`;
        const $cell = $(selector).first();
        if ($cell.length) this._openEditor($cell);
    }
}
