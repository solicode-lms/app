// Ce fichier est maintenu par ESSARRAJ Fouad

import { CrudAction } from "../../actions/CrudAction";
import EventUtil from "../../utils/EventUtil";
import { LoadingIndicator } from "../LoadingIndicator";
import { NotificationHandler } from "../NotificationHandler";

/**
 * Gère l'édition inline d'un champ dans un tableau CRUD.
 */
export class InlineEdit2 extends CrudAction {
    constructor(config, tableUI) {
        super(config, tableUI);
        this.config = config;
        this.tableUI = tableUI;
        this.entityEditor = tableUI.entityEditor;
        this.activeCell = null;
    }

    init() {
        this.loader = new LoadingIndicator(this.config.tableSelector);
        this._bindInlineEditEvents();
    }

    _bindInlineEditEvents() {
        const selector = `${this.config.tableSelector} .editable-cell`;

        // Double-clic ouvre l’éditeur
        EventUtil.bindEvent("dblclick", selector, (e) => {
            e.preventDefault();
            const $cell = $(e.currentTarget);

            // Si une autre cellule est active → commit avant
            if (this.activeCell && !this.activeCell.is($cell)) {
                this._submitActiveCell();
                return;
            }

            this._openEditor($cell);
        });

        // Clic ailleurs → commit
        EventUtil.bindEvent("click", "body", (e) => {
            if (!this.activeCell) return;
            if (!$(e.target).closest(this.activeCell).length) {
                this._submitActiveCell();
            }
        });

        // Hover feedback
        EventUtil.bindEvent("mouseenter", selector, (e) =>
            $(e.currentTarget).css({ cursor: "cell", backgroundColor: "#e9ecef" })
        );
        EventUtil.bindEvent("mouseleave", selector, (e) =>
            $(e.currentTarget).css({ cursor: "", backgroundColor: "" })
        );
    }

    /**
     * Ouvre un éditeur inline en appelant l’API meta
     */
    async _openEditor($cell) {
        if ($cell.data("original") !== undefined) return;
        const field = $cell.data("field");
        const id = $cell.data("id");
        if (!field || !id) return;

        this._cancelEdit();
        this.activeCell = $cell;

        try {
            this.loader.showNomBloquante("Chargement…");

            // Appel API meta
            const resp = await fetch(
                `/admin/PkgRealisationTache/realisationTaches/${id}/field/${field}/meta`
            );
            if (!resp.ok) throw new Error("Erreur chargement meta");
            const meta = await resp.json();

            // Création input adapté
            const value = meta.value ?? "";
            let $input;
            switch (meta.type) {
                case "text":
                    $input = $(`<input type="text" class="form-control form-control-sm">`).val(value);
                    break;
                case "number":
                    $input = $(`<input type="number" class="form-control form-control-sm">`).val(value);
                    break;
                case "date":
                    $input = $(`<input type="date" class="form-control form-control-sm">`).val(value);
                    break;
                case "boolean":
                    $input = $(`<input type="checkbox" class="form-check-input">`).prop("checked", !!value);
                    break;
                case "select":
                    $input = $(`<select class="form-control form-control-sm"></select>`);
                    (meta.options?.values || []).forEach((opt) => {
                        $input.append(
                            $("<option>")
                                .val(opt.value)
                                .text(opt.label)
                                .prop("selected", String(opt.value) === String(value))
                        );
                    });
                    break;
                default:
                    NotificationHandler.showError(`Type d'éditeur non supporté: ${meta.type}`);
                    this.activeCell = null;
                    return;
            }

            // Remplace le contenu de la cellule
            $cell.data("original", $cell.html()).empty().append($input);
            if ($input.is(":checkbox")) $input.focus();
            else $input.trigger("focus");

            // Events
            $input.on("keydown.inlineEdit", (evt) => {
                if (evt.key === "Escape") this._cancelEdit();
                if (evt.key === "Enter") this._submitActiveCell();
            });
            if ($input.is("select") || $input.is(":checkbox")) {
                $input.on("change.inlineEdit", () => this._submitActiveCell());
            }

        } catch (err) {
            console.error("Erreur ouverture inline:", err);
            NotificationHandler.showError("Erreur ouverture éditeur inline.");
            this.activeCell = null;
        } finally {
            this.loader.hide();
        }
    }

    /**
     * Sauvegarde la cellule active via PATCH API
     */
    async _submitActiveCell() {
        if (!this.activeCell) return;
        const $cell = this.activeCell;
        const field = $cell.data("field");
        const id = $cell.data("id");
        const $input = $cell.find("input, select");

        let newValue;
        if ($input.is(":checkbox")) newValue = $input.is(":checked");
        else newValue = $input.val();

        try {
            const res = await fetch(
                `/admin/PkgRealisationTache/realisationTaches/${id}/inline/${field}`,
                {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        "If-Match": $cell.data("etag") || "",
                    },
                    body: JSON.stringify({ changes: { [field]: newValue } }),
                }
            );

            if (res.status === 409) {
                NotificationHandler.showError("Conflit de mise à jour (ETag).");
                this._cancelEdit();
                return;
            }
            if (!res.ok) throw new Error("Erreur sauvegarde inline");

            const data = await res.json();
            $cell.html(data.display[field]?.text ?? newValue);
            $cell.data("etag", data.etag);
            NotificationHandler.showSuccess("Mise à jour réussie ✅");
        } catch (err) {
            console.error(err);
            NotificationHandler.showError("Erreur mise à jour inline.");
            this._cancelEdit();
        } finally {
            this.activeCell = null;
        }
    }

    _cancelEdit() {
        if (!this.activeCell) return;
        const $cell = this.activeCell;
        const orig = $cell.data("original");
        if (orig !== undefined) {
            $cell.off(".inlineEdit").empty().html(orig);
            $cell.removeData("original");
        }
        this.activeCell = null;
    }
}
