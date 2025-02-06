import ConfigHelper from "./ConfigHelper";

export default class ConfigWithTabPanelManageHelper extends ConfigHelper {
    constructor(config, contextState, sessionState) {

        super(config, contextState, sessionState);

        this.id = `${this.entity_name}-edit-has-many`;

    
        this.cardTabSelector = this.cardTabSelector;
        this.hasTabs = true;
    }

 
}
