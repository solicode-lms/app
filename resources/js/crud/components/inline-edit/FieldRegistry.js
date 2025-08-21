// Ce fichier est maintenu par ESSARRAJ Fouad

/**
 * Contrat attendu pour un éditeur :
 * - mount(container, props): installe l'UI
 * - destroy(): nettoyage
 * - focus(): focus l'input si possible
 *
 * props = {
 *   meta: FieldMeta,
 *   value: any,
 *   onCommit: (newValue) => void,
 *   onCancel: () => void,
 *   autoFocus?: boolean
 * }
 */
class FieldRegistry {
    constructor() {
        this.map = new Map();
    }

    /**
     * Enregistre une factory pour un type d’éditeur
     */
    register(type, factory) {
        this.map.set(type, factory);
    }

    /**
     * Crée un éditeur pour un champ donné
     */
    create(type, props) {
        if (!this.map.has(type)) {
            throw new Error(`Aucun éditeur enregistré pour le type ${type}`);
        }
        return this.map.get(type)(props);
    }
}

export const fieldRegistry = new FieldRegistry();

//
// =======================
// Implémentations basiques
// =======================
//

/** Text input */
fieldRegistry.register('text', (props) => {
    let input;
    return {
        mount(container, { value, onCommit, onCancel, autoFocus }) {
            input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.value = value ?? '';

            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') onCancel();
                if (e.key === 'Enter') onCommit(input.value);
            });
            input.addEventListener('blur', () => onCommit(input.value));

            container.innerHTML = '';
            container.appendChild(input);
            if (autoFocus) input.focus();
        },
        destroy() { input = null; },
        focus() { input?.focus(); }
    };
});

/** Number input */
fieldRegistry.register('number', (props) => {
    let input;
    return {
        mount(container, { value, onCommit, onCancel, autoFocus }) {
            input = document.createElement('input');
            input.type = 'number';
            input.className = 'form-control form-control-sm';
            input.value = value ?? '';

            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') onCancel();
                if (e.key === 'Enter') onCommit(input.valueAsNumber);
            });
            input.addEventListener('blur', () => onCommit(input.valueAsNumber));

            container.innerHTML = '';
            container.appendChild(input);
            if (autoFocus) input.focus();
        },
        destroy() { input = null; },
        focus() { input?.focus(); }
    };
});

/** Date input */
fieldRegistry.register('date', (props) => {
    let input;
    return {
        mount(container, { value, onCommit, onCancel, autoFocus }) {
            input = document.createElement('input');
            input.type = 'date';
            input.className = 'form-control form-control-sm';
            input.value = value ?? '';

            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') onCancel();
                if (e.key === 'Enter') onCommit(input.value);
            });
            input.addEventListener('blur', () => onCommit(input.value));

            container.innerHTML = '';
            container.appendChild(input);
            if (autoFocus) input.focus();
        },
        destroy() { input = null; },
        focus() { input?.focus(); }
    };
});

/** Boolean (checkbox) */
fieldRegistry.register('boolean', (props) => {
    let input;
    return {
        mount(container, { value, onCommit, onCancel }) {
            input = document.createElement('input');
            input.type = 'checkbox';
            input.checked = !!value;

            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') onCancel();
                if (e.key === 'Enter') onCommit(input.checked);
            });
            input.addEventListener('change', () => onCommit(input.checked));

            container.innerHTML = '';
            container.appendChild(input);
            input.focus();
        },
        destroy() { input = null; },
        focus() { input?.focus(); }
    };
});

/** Select (options statiques) */
fieldRegistry.register('select', (props) => {
    let select;
    return {
        mount(container, { meta, value, onCommit, onCancel }) {
            select = document.createElement('select');
            select.className = 'form-control form-control-sm';

            (meta.options?.values || []).forEach(opt => {
                const option = document.createElement('option');
                option.value = String(opt.value);
                option.textContent = opt.label;
                if (String(opt.value) === String(value)) option.selected = true;
                select.appendChild(option);
            });

            select.addEventListener('keydown', e => {
                if (e.key === 'Escape') onCancel();
                if (e.key === 'Enter') onCommit(select.value);
            });
            select.addEventListener('change', () => onCommit(select.value));

            container.innerHTML = '';
            container.appendChild(select);
            select.focus();
        },
        destroy() { select = null; },
        focus() { select?.focus(); }
    };
});

/** String input (alias text) */
fieldRegistry.register('string', (props) => {
    let input;
    return {
        mount(container, { value, onCommit, onCancel, autoFocus }) {
            input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control form-control-sm';
            input.value = value ?? '';

            input.addEventListener('keydown', e => {
                if (e.key === 'Escape') onCancel();
                if (e.key === 'Enter') onCommit(input.value);
            });
            input.addEventListener('blur', () => onCommit(input.value));

            container.innerHTML = '';
            container.appendChild(input);
            if (autoFocus) input.focus();
        },
        destroy() { input = null; },
        focus() { input?.focus(); }
    };
});