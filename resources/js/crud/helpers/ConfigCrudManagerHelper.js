import ConfigHelper from './ConfigHelper';

export default class ConfigCrudManagerHelper extends ConfigHelper {

    constructor(config) {
        super(config);

        this.id = `${this.entity_name}-crud`;

        // indique si la page CRUD est situé dans tab panel
        this.isMany = config.isMany;
        this.editOnFullScreen = config.editOnFullScreen;
        this.edit_has_many = config.edit_has_many;

        this.crudSelector = config.crudSelector;
        this.tableSelector = config.tableSelector;
        this.filterFormSelector = config.filterFormSelector;
        this.createUrl = config.createUrl;
        this.showUrl = config.showUrl;
        this.storeUrl = config.storeUrl;
        this.updateAttributesUrl = config.updateAttributesUrl
        
        this.deleteUrl = config.deleteUrl;
        this.createTitle = config.create_title;
        this.calculationUrl = config.calculationUrl;


        this.canEdit = config.canEdit;


        this.searchInputSelector = `${this.crudSelector} .crud-search-input`;
        this.paginationSelector = `${this.crudSelector} .pagination`;
        this.dataContainerSelector = `${this.tableSelector}`;
        this.dataContainerOutSelector = `${this.tableSelector}-out`;
        this.filterIconSelector = `${this.filterFormSelector} .filter-icon`;
        this.sortableColumnSelector = `${this.tableSelector} .sortable-column`;
    }
}