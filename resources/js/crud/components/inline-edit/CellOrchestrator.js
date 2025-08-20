// Ce fichier est maintenu par ESSARRAJ Fouad
import { CrudAction } from "../../actions/CrudAction";
import { LoadingIndicator } from "../LoadingIndicator";
import { NotificationHandler } from "../NotificationHandler";
import { fieldRegistry } from "./FieldRegistry";
import { metaCache } from "./MetaCache";

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
        document.querySelector(tableSelector)?.addEventListener("dblclick", (e) => {
            const td = e.target.closest(".editable-cell");
            if (!td) return;
            this.activateCell(td);
        });

        // Clic hors table → annuler édition
        // document.addEventListener("click", (e) => {
        //     if (!this.active) return;
        //     if (!e.target.closest(tableSelector)) {
        //         this.cancelEdit();
        //     }
        // });

        // Gestion clavier dans les inputs
        document.addEventListener("keydown", (e) => {
            if (!this.active) return;
            if (e.key === "Escape") this.cancelEdit();
            if (e.key === "Enter") {
                e.preventDefault();
                const input = this.active.querySelector("input,select,textarea");
                this.commitChange(this.active, this.active._meta, input?.value);
            }
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
            // 1. Charger les metas depuis le cache ou API
            const meta = await metaCache.getMeta("realisationTache", id, field);

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
            NotificationHandler.showError("Impossible d'activer l’édition.");
            console.error("Erreur activation cellule:", err);
        }
    }

    async commitChange(td, meta, newValue) {
        const id = td.dataset.id;
        const field = td.dataset.field;

        // Optimistic UI : affichage provisoire
        // Récupérer contenu initial depuis data-original
        const oldContent = td.dataset.original ?? td.innerHTML;

        // td.textContent = newValue;
        td.classList.add("updating");
        this.loader.showNomBloquante();

        try {

            let editUrl = this.getUrlWithId(this.config.editUrl, id); // Générer l'URL dynamique
            editUrl = `/admin/PkgRealisationTache/realisationTaches/${id}/inline`;
            editUrl = this.appendParamsToUrl(
                editUrl,
                this.viewStateService.getContextParams()
            );


            const res = await fetch(
                editUrl,
                {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "If-Match": meta.etag,
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({ changes: { [field]: newValue } }),
                }
            );

            if (res.status === 409) {
                alert("⚠️ Conflit de version (ETag). Recharge la ligne.");
                td.textContent = oldContent;
                td.classList.remove("updating");
                return;
            }

            if (!res.ok) {
                throw new Error(`Erreur HTTP ${res.status} - ${res}`);
            }

            const data = await res.json();

            // ✅ Mettre à jour le rendu
            td.innerHTML = data.display[field]?.html ?? newValue;
            td.classList.remove("updating");

            // ✅ Mettre à jour le cache meta avec le nouvel etag
            meta.etag = data.etag;
            meta.value = newValue;
            metaCache.set("realisationTache", id, field, meta);
        } catch (err) {
            console.error("Erreur PATCH inline:", err);
            td.innerHTML = oldContent; // rollback
            td.classList.remove("updating");
            NotificationHandler.showError("Erreur lors de la mise à jour." +  err.message);
        } finally {
            td.classList.remove("updating");
            this.loader.hide();
            this.active = null;
            this.editor = null;
        }
    }

    cancelEdit() {
        if (!this.active || !this.editor) return;
        // rollback contenu
        this.active.innerHTML = this.active.dataset.original || this.active.textContent;
        this.editor.destroy();
        this.active = null;
        this.editor = null;
    }
}

 