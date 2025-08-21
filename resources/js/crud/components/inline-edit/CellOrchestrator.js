// Ce fichier est maintenu par ESSARRAJ Fouad
import { CrudAction } from "../../actions/CrudAction";
import EventUtil from "../../utils/EventUtil";
import { AjaxErrorHandler } from "../AjaxErrorHandler";
import { LoadingIndicator } from "../LoadingIndicator";
import { NotificationHandler } from "../NotificationHandler";
import { fieldRegistry } from "./FieldRegistry";
import { MetaCache } from "./MetaCache";
import { LoadListAction } from './../../actions/LoadListAction';

/**
 * CellOrchestrator
 * - Gère l’édition inline d’une ou plusieurs cellules actives
 * - Navigation Enter / Escape / Tab / Shift+Tab
 * - Optimistic UI avec rollback si erreur
 */
export class CellOrchestrator extends CrudAction {

    constructor(config, tableUI) {
        super(config, tableUI);
        this.config = config;
        this.tableUI = tableUI;

        this.active = null;   // cellule en cours d’édition
        this.editor = null;   // éditeur monté

        this.metaCache = new MetaCache(config);
        this.loader = new LoadingIndicator(this.config.tableSelector);

        this.debounceTimer = null;

        // 🔹 compteur d’édition en cours
        this.editCount = 0;
    }

    /**
     * Getter : savoir si au moins une cellule est en édition
     */
    get isEditing() {
        return this.editCount > 0;
    }

    init() {
        this.bindTable(this.config.tableSelector);
    }

    /**
     * Attache les événements uniquement sur un tableau donné
     */
    bindTable(tableSelector) {
        let clickTimer = null;

        const selector = `${tableSelector} .editable-cell`;

        // Double-clic sur cellule → activer l’éditeur
        EventUtil.bindEvent("dblclick", tableSelector, e => {
            const td = e.target.closest(".editable-cell");
            if (!td) return;

            // ✅ Autoriser le dblclick si c’est une nouvelle cellule
            if (this.active && this.active === td) return;

            clearTimeout(clickTimer);
            this.activateCell(td);
        });

        // Clic hors table → annuler édition
        EventUtil.bindEvent("click", document, e => {
            if (!this.active) 
                return;

            if (e.detail === 1) {
                clickTimer = setTimeout(() => {
                    if (!this.active.contains(e.target)) {
                        this.cancelEdit();
                    }
                }, 500);
            }
        });

        // Navigation clavier
        EventUtil.bindEvent("keydown", document, e => {
            if (!this.active) return;

            // if (e.key === "Tab") {
            //     e.preventDefault();
            //     const next = this.findAdjacentCell(this.active, !e.shiftKey, false);
            //     if (next) this.activateCell(next, true);
            // }

            // if (e.key === "Enter") {
            //     e.preventDefault();
            //     const next = this.findAdjacentCell(this.active, !e.shiftKey, true);
            //     if (next) this.activateCell(next, true);
            // }
        });

         // survol
        EventUtil.bindEvent('mouseenter', selector, e => {
            $(e.currentTarget).css({ cursor: 'cell' });
        });
        EventUtil.bindEvent('mouseleave', selector, e => {
            $(e.currentTarget).css({ cursor: '' });
        });
    }

    /**
     * Activation d’une cellule en édition
     */
    async activateCell(td, fromNavigation = false) {
        if (this.active && this.active !== td) {
            this.cancelEdit(fromNavigation);
        }

        const id = td.dataset.id;
        const field = td.dataset.field;

        try {
            this.loader.showNomBloquante("");
            const meta = await this.metaCache.getMeta("realisationTache", id, field);
            this.loader.hide();

            if (!td.dataset.original) {
                td.dataset.original = td.innerHTML;
            }

            this.editor = fieldRegistry.create(meta.type, {});
            const value = meta.value ?? td.textContent;

            this.active = td;

            // ✅ nouvelle édition → incrémenter compteur
            this.editCount++;

            this.editor.mount(td, {
                meta,
                value,
                autoFocus: true,
                onCommit: newValue => this.commitChange(td, meta, newValue),
                onCancel: () => this.cancelEdit(),
            });
        } catch (err) {
            AjaxErrorHandler.handleError(err, "Impossible d'activer l’édition.");
            console.error("Erreur activation cellule:", err);
            this.loader.hide();
        }
    }

    /**
     * Validation d’une cellule
     */
    async commitChange(td, meta, newValue) {
        const id = td.dataset.id;
        const field = td.dataset.field;
        const oldContent = td.dataset.original ?? td.innerHTML;

        clearTimeout(this.debounceTimer);

        this.debounceTimer = setTimeout(() => {
            td.classList.add("updating");
            this.loader.showNomBloquante("Mise à jour en cours...");

            let patchInlineUrl = this.getUrlWithId(this.config.patchInlineUrl, id);
            patchInlineUrl = this.appendParamsToUrl(patchInlineUrl, this.viewStateService.getContextParams());

            $.ajax({
                url: patchInlineUrl,
                method: "PATCH",
                headers: {
                    "If-Match": meta.etag,
                    "X-CSRF-TOKEN": this.config.csrfToken
                },
                contentType: "application/json",
                data: JSON.stringify({ changes: { [field]: newValue } }),
            })
                .done((data) => {
                    const traitement_token = data?.traitement_token;
                    if (traitement_token) {
                        this.pollTraitementStatus(traitement_token, () => {});
                    }

                    td.innerHTML = data.display[field]?.html ?? newValue;
                    td.classList.remove("updating");

                    meta.etag = data.etag;
                    meta.value = newValue;
                    this.metaCache.set("realisationTache", id, field, meta);

                    NotificationHandler.showSuccess("Valeur mise à jour avec succès.");
                })
                .fail((xhr) => {
                    td.innerHTML = oldContent;
                    td.classList.remove("updating");

                    if (xhr.status === 409) {
                        NotificationHandler.showError("⚠️ Conflit de version. Rechargez la ligne.");
                        return;
                    }
                    AjaxErrorHandler.handleError(xhr, "Erreur lors de la mise à jour.");
                })
                .always(() => {
                    this.loader.hide();
                    this.active = null;
                    this.editor = null;

                    // ✅ Attendre 200ms avant de terminer l’édition
                    setTimeout(() => {
                        this.editCount = Math.max(0, this.editCount - 1);
                        this.tableUI.loadListAction.loadEntities();
                    }, 3000);

                });
        }, 500);
    }

    /**
     * Annulation d’une cellule
     */
    cancelEdit(fromNavigation = false) {
        if (!this.active || !this.editor) {
            if (!fromNavigation) this.editCount = Math.max(0, this.editCount - 1);
            return;
        }

        this.active.innerHTML = this.active.dataset.original || this.active.textContent;
        this.editor.destroy();
        this.active = null;
        this.editor = null;

        if (!fromNavigation) {
            this.editCount = Math.max(0, this.editCount - 1);
        }
    }

    /**
     * Trouve la cellule voisine
     */
    findAdjacentCell(td, forward = true, vertical = false) {
        if (!td) return null;
        const row = td.parentElement;
        if (!row) return null;

        if (!vertical) {
            const cells = Array.from(row.querySelectorAll(".editable-cell"));
            const idx = cells.indexOf(td);
            if (idx === -1) return null;

            const nextIdx = forward ? idx + 1 : idx - 1;
            if (nextIdx < 0 || nextIdx >= cells.length) return null;

            return cells[nextIdx];
        } else {
            const tableBody = row.parentElement;
            if (!tableBody) return null;

            const cells = Array.from(row.querySelectorAll(".editable-cell"));
            const idx = cells.indexOf(td);
            if (idx === -1) return null;

            const allRows = Array.from(tableBody.querySelectorAll("tr"));
            const rowIdx = allRows.indexOf(row);
            if (rowIdx === -1) return null;

            const targetRow = forward ? allRows[rowIdx + 1] : allRows[rowIdx - 1];
            if (!targetRow) return null;

            const targetCells = Array.from(targetRow.querySelectorAll(".editable-cell"));
            return targetCells[idx] || null;
        }
    }
}
