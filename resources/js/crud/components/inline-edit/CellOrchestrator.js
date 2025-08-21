// Ce fichier est maintenu par ESSARRAJ Fouad
import { CrudAction } from "../../actions/CrudAction";
import EventUtil from "../../utils/EventUtil";
import { AjaxErrorHandler } from "../AjaxErrorHandler";
import { LoadingIndicator } from "../LoadingIndicator";
import { NotificationHandler } from "../NotificationHandler";
import { fieldRegistry } from "./FieldRegistry";
import { MetaCache } from "./MetaCache";

/**
 * CellOrchestrator
 * - Gère l’édition inline d’une seule cellule active
 * - Navigation Enter / Escape / Tab / Shift+Tab
 * - Optimistic UI avec rollback si erreur
 */
export class CellOrchestrator extends CrudAction {

    constructor(config, tableUI) {
        super(config,tableUI);
        this.config = config;
        this.tableUI = tableUI;
        this.active = null; // cellule en cours d’édition
        this.editor = null; // éditeur monté

        this.metaCache = new MetaCache(config);
        // Loader lié à la table
        this.loader = new LoadingIndicator(this.config.tableSelector);
    }

    init() {
        this.bindTable(this.config.tableSelector);
    }

    /**
     * Attache les événements uniquement sur un tableau donné
     */
    bindTable(tableSelector) {
        // Double-clic sur cellule → activer l’éditeur
        EventUtil.bindEvent('dblclick', tableSelector, e => {
            const td = e.target.closest(".editable-cell");
            if (!td) return;
            this.activateCell(td);
        });

        // Clic hors table → annuler édition
        EventUtil.bindEvent('click', document, e => {
            if (!this.active) return;

            // Si on clique en dehors de la cellule active → annuler
            if (!this.active.contains(e.target)) {
                this.cancelEdit();
            }
        });

        // Gestion clavier dans les inputs
        EventUtil.bindEvent('keydown', document, (e) => {
            if (!this.active) return;

            // init avec editor
            // if (e.key === "Escape") this.cancelEdit();
            // if (e.key === "Enter") {
            //     e.preventDefault();
            //     const input = this.active.querySelector("input,select,textarea");
            //     this.commitChange(this.active, this.active._meta, input?.value);
            // }
           if (e.key === "Tab") {
                e.preventDefault();
                // Tab = horizontal, Shift+Tab = arrière
                const next = this.findAdjacentCell(this.active, !e.shiftKey, false);
                if (next) this.activateCell(next);
                this.active = next;
            }

            if (e.key === "Enter") {
                e.preventDefault();
                // Enter = vertical, Shift+Enter = monter
                const next = this.findAdjacentCell(this.active, !e.shiftKey, true);
                if (next) this.activateCell(next);
                this.active = next;
            }


            // 2. Pas d’édition active → ouverture via raccourcis
            // const focusCell = e.target.closest(".editable-cell");
            // if (!focusCell) return;

            // if (e.key === "Enter" || e.key === "F2") {
            //     e.preventDefault();
            //     this.activateCell(focusCell);
            // }

        });
    }

    async activateCell(td) {
        // Annuler l’éditeur actif précédent
        if (this.active && this.active !== td) {
            this.cancelEdit();
        }

        const id = td.dataset.id;
        const field = td.dataset.field;

        try {

            this.loader.showNomBloquante("");
            // 1. Charger les metas depuis le cache ou API
            const meta = await this.metaCache.getMeta("realisationTache", id, field);
            this.loader.hide();

            // ✅ Sauvegarder le contenu original AVANT de remplacer
            if (!td.dataset.original) {
                td.dataset.original = td.innerHTML;
            }

            // 2. Créer l’éditeur
            this.editor = fieldRegistry.create(meta.type, {});
            const value = meta.value ?? td.textContent;

            this.active = td;
            this.editor.mount(td, {
                meta,
                value,
                autoFocus: true,
                onCommit: (newValue) => this.commitChange(td, meta, newValue),
                onCancel: () => this.cancelEdit(),
            });
        } catch (err) {
            AjaxErrorHandler.handleError(err, "Impossible d'activer l’édition.");
            console.error("Erreur activation cellule:", err);
        }
    }

    async commitChange(td, meta, newValue) {
        const id = td.dataset.id;
        const field = td.dataset.field;
        const oldContent = td.dataset.original ?? td.innerHTML;

        td.classList.add("updating");
        this.loader.showNomBloquante("Mise à jour en cours...");

        let editUrl = this.getUrlWithId(this.config.editUrl, id);
        editUrl = `/admin/PkgRealisationTache/realisationTaches/${id}/inline`;
        editUrl = this.appendParamsToUrl(editUrl, this.viewStateService.getContextParams());

        $.ajax({
            url: editUrl,
            method: "PATCH",
            headers: {
                "If-Match": meta.etag,
                "X-CSRF-TOKEN": this.config.csrfToken
            },
            contentType: "application/json",
            data: JSON.stringify({ changes: { [field]: newValue } }),
        })
            .done((data) => {


                // Affichage de message de progression de traitement
                const traitement_token = data?.traitement_token;
                if (traitement_token) {
                    this.pollTraitementStatus(traitement_token, () => {
                       ;
                    });
                }else{
                    
                }


                // ✅ Mettre à jour le rendu
                td.innerHTML = data.display[field]?.html ?? newValue;
                td.classList.remove("updating");

                // ✅ Mettre à jour le cache meta
                meta.etag = data.etag;
                meta.value = newValue;
                this.metaCache.set("realisationTache", id, field, meta);

                NotificationHandler.showSuccess("Valeur mise à jour avec succès.");
            })
            .fail((xhr) => {
                td.innerHTML = oldContent; // rollback
                td.classList.remove("updating");

                // Si conflit ETag
                if (xhr.status === 409) {
                    NotificationHandler.showError("⚠️ Conflit de version. Rechargez la ligne.");
                    return;
                }

                // Utiliser ton gestionnaire global
                AjaxErrorHandler.handleError(xhr, "Erreur lors de la mise à jour.");
            })
            .always(() => {
                this.loader.hide();
                this.active = null;
                this.editor = null;
            });
    }

    cancelEdit() {
        if (!this.active || !this.editor) return;
        // rollback contenu
        this.active.innerHTML = this.active.dataset.original || this.active.textContent;
        this.editor.destroy();
        this.active = null;
        this.editor = null;
    }

  /**
     * Trouve la cellule editable voisine
     * @param {HTMLTableCellElement} td - cellule courante
     * @param {boolean} forward - true = avancer (droite ou bas), false = reculer (gauche ou haut)
     * @param {boolean} vertical - false = horizontal (Tab), true = vertical (Enter)
     * @returns {HTMLTableCellElement|null}
     */
    findAdjacentCell(td, forward = true, vertical = false) {
        if (!td) return null;
        const row = td.parentElement;
        if (!row) return null;

        if (!vertical) {
            // --- Navigation horizontale (dans la même ligne)
            const cells = Array.from(row.querySelectorAll(".editable-cell"));
            const idx = cells.indexOf(td);
            if (idx === -1) return null;

            const nextIdx = forward ? idx + 1 : idx - 1;
            if (nextIdx < 0 || nextIdx >= cells.length) return null;

            return cells[nextIdx];
        } else {
            // --- Navigation verticale (même colonne dans la ligne suivante / précédente)
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

 