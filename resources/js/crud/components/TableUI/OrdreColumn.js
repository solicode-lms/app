import { NotificationHandler } from "../NotificationHandler";

export class OrdreColumn {
    constructor(config, tableUI) {
        this.config = config;
        this.tableUI = tableUI;
        this.entityEditor = this.tableUI.entityEditor; // fourni via CrudManager
    }

    init() {
        if(this.config.canEdit){
             this.initSortable();
        }
    }

    initSortable() {
        const self = this;

        $(`${this.config.tableSelector} tbody`).sortable({
            items: "tr",
            cursor: "move",
            axis: "y",
            handle: ".sortable-button", // la classe HTML définie sur <span>
            placeholder: "sortable-placeholder",
            forcePlaceholderSize: true,
            tolerance: "pointer",
            update: function (event, ui) {
                const trElement = ui.item;
                const id = trElement.attr('id')?.replace(/^.*-/, ''); // ex: "workflowTache-row-5" → "5"

                if (!id) return;

                const newPosition = trElement.index() + 1;

                self.entityEditor.update_attributes(
                    { id: id, ordre: newPosition },
                    () => {
                        NotificationHandler.showSuccess('Ordre mis à jour.');
                        self.tableUI.entityLoader.loadEntities();
                    }
                );
            }
        });

        $(`${this.config.tableSelector} .sortable-button`).css('cursor', 'move');
    }
}
