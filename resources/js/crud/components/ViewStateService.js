export class ViewStateService {
    /**
     * Constructeur pour initialiser ViewStateService.
     */
    constructor() {
        this.init();
    }

    init() {
        this.contextState = window.contextState || { scope: {}, form: {}, table: {}, filter: {}, global: {} };
    }

    getVariablesByType(type, modelName) {
        const allVariables = this.contextState[type] || {};
        const globalVariables = this.contextState[type]?.global || {};
        return { ...globalVariables, ...allVariables[modelName] };
    }

    getScopeVariables(modelName) {
        return this.getVariablesByType('scope', modelName);
    }

    getFormVariables(modelName) {
        return this.getVariablesByType('form', modelName);
    }

    getTableVariables(modelName) {
        return this.getVariablesByType('table', modelName);
    }

    getFilterVariables(modelName) {
        return this.getVariablesByType('filter', modelName);
    }

    addVariable(type, modelName, key, value) {
        if (!this.contextState[type]) {
            this.contextState[type] = {};
        }
        if (!this.contextState[type][modelName]) {
            this.contextState[type][modelName] = {};
        }
        this.contextState[type][modelName][key] = value;
    }

    addData(type, modelName, data) {
        if (!this.contextState[type]) {
            this.contextState[type] = {};
        }
        if (!this.contextState[type][modelName]) {
            this.contextState[type][modelName] = {};
        }
        Object.entries(data).forEach(([key, value]) => {
            this.contextState[type][modelName][key] = value;
        });
    }

    updateContext(newState) {
        this.contextState = { ...this.contextState, ...newState };
    }

    getContextParams() {
        const params = new URLSearchParams();
        Object.entries(this.contextState).forEach(([type, models]) => {
            Object.entries(models).forEach(([modelName, values]) => {
                Object.entries(values).forEach(([key, value]) => {
                    params.append(`${type}.${modelName}.${key}`, value);
                });
            });
        });
        return params.toString();
    }

    addContextToConfig(config) {
        const updatedConfig = { ...config };
        const contextParams = this.getContextParams();
        
        Object.keys(updatedConfig).forEach((key) => {
            if (key.toLowerCase().endsWith('url') && typeof updatedConfig[key] === 'string') {
                const url = new URL(updatedConfig[key], window.location.origin);
                const separator = url.search ? '&' : '?';
                updatedConfig[key] = `${url.toString()}${separator}${contextParams}`;
            }
        });
        return updatedConfig;
    }

    toString() {
        return JSON.stringify(this.contextState);
    }
}
