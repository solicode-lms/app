// Ce fichier est maintenu par ESSARRAJ Fouad
import { CellOrchestrator } from './CellOrchestrator';


/**
 * InlineEdit
 * Point d’entrée qui initialise l’orchestrateur des cellules éditables
 */
export class InlineEdit {
    constructor(config, tableUI) {
        this.config = config;
        this.tableUI = tableUI;
        this.cellOrchestrator = new CellOrchestrator();

    }

    init() {
        console.log("🔧 InlineEdit prêt : binding sur .editable-cell");
        this.cellOrchestrator.bindTable(this.config.tableSelector, this.tableUI);
    }
}