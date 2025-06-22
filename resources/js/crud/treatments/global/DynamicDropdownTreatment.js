import { AjaxErrorHandler } from "../../components/AjaxErrorHandler";
import { LoadingIndicator } from "../../components/LoadingIndicator";
import EventUtil from "../../utils/EventUtil";

/**
 * Gère un dropdown dynamique pouvant cibler plusieurs éléments,
 * chacun avec son propre endpoint et paramètre de filtre.
 */
export default class DynamicDropdownTreatment {
  /**
   * @param {HTMLElement} triggerElement élément déclencheur (select)
   * @param {Object} config configuration optionnelle (formSelector, filterFormSelector)
   */
  constructor(triggerElement, config = {}) {
    this.triggerElement = triggerElement;
    this.config = config;

    // Parser les listes CSV dans data-attributes
    this.targets = this._parseCsv(
      triggerElement.dataset.targetDynamicDropdown,
      triggerElement.dataset.targetDynamicDropdownApiUrl,
      triggerElement.dataset.targetDynamicDropdownFilter
    );

    if (!this.targets.length) {
      console.warn(
        'DynamicDropdownTreatment : attributs `data-target-dynamic-dropdown`, ' +
        '`data-target-dynamic-dropdown-api-url` ou ' +
        '`data-target-dynamic-dropdown-filter` manquants ou mal formés.'
      );
      return;
    }

    // Loader global
    const container =
      config.formSelector && document.querySelector(config.formSelector)
        ? config.formSelector
        : config.filterFormSelector && document.querySelector(config.filterFormSelector)
        ? config.filterFormSelector
        : '#card_crud';
    this.loader = new LoadingIndicator(container);

    this.init();
  }

  /**
   * Transforme les chaînes CSV en tableau d'objets { selector, apiUrl, filterParam, cache }
   */
  _parseCsv(selectorsCsv, urlsCsv, filtersCsv) {
    if (!selectorsCsv || !urlsCsv || !filtersCsv) return [];

    const selectors = selectorsCsv.split(',').map(s => s.trim());
    const urls = urlsCsv.split(',').map(u => u.trim());
    const filters = filtersCsv.split(',').map(f => f.trim());

    const length = Math.max(selectors.length, urls.length, filters.length);
    return Array.from({ length }, (_, i) => ({
      selector: selectors[i] || selectors[0],
      apiUrl: urls[i] || urls[0],
      filterParam: filters[i] || filters[0],
      cache: new Map()
    }));
  }

  /**
   * Initialise l'écouteur et charge si valeur initiale
   */
  init() {
    const name = this.triggerElement.name;
    EventUtil.bindEvent('change', `[name='${name}']`, async e => {
      await this.updateAll(e.target.value);
    });

    if (this.triggerElement.value) {
      this.updateAll(this.triggerElement.value);
    }
  }

  /**
   * Met à jour toutes les cibles pour la valeur donnée
   * @param {string} value valeur sélectionnée
   */
  async updateAll(value) {
    if (!value) return;

    for (const tgt of this.targets) {
      const el = document.querySelector(tgt.selector);
      if (!el) {
        console.warn(`DynamicDropdownTreatment : cible introuvable "${tgt.selector}"`);
        continue;
      }

   

      // Skip si déjà en cache
      if (tgt.cache.has(value)) {

        // ▸ Désactiver la cible avant l'appel
        el.disabled = true;

        this._populate(el, tgt.cache.get(value));

        // ▸ Réactiver immédiatement si mise en cache
        el.disabled = false;
        // if ($(el).hasClass('select2-hidden-accessible')) {
        //   $(el).trigger('change.select2');
        // }
        continue;
      }

      // Chargement
      this.loader.showNomBloquante();
      try {
        const url = `${tgt.apiUrl}?filter=${tgt.filterParam}&value=${encodeURIComponent(value)}`;
      
        el.disabled = true;
        const resp = await fetch(url);
        if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
        const data = await resp.json();

        tgt.cache.set(value, data);
        this._populate(el, data);
      
      } catch (err) {
        AjaxErrorHandler.handleError(err, err.message);
      } finally {
        el.disabled = false;
        this.loader.hide();
      }
    }
  }

  /**
   * Remplit un <select> avec les données fournies
   * @param {HTMLSelectElement} selectEl
   * @param {Array<{id: string, label: string}>} items
   */
/**
 * Remplit un <select> avec les données fournies
 * @param {HTMLSelectElement} selectEl
 * @param {Array<{id: string, label: string}>} items
 */
_populate(selectEl, items) {
  // ① Récupérer la ou les valeurs précédentes
  const isMultiple = selectEl.multiple;
  const prev = isMultiple
    ? Array.from(selectEl.selectedOptions).map(opt => opt.value)
    : selectEl.value;

  // ② Vider le <select>
  selectEl.innerHTML = '';

  // ③ Si ce n’est PAS un multiple, ajouter l’option vide
  if (!isMultiple) {
    const empty = selectEl.querySelector('option[value=""]') || new Option('', '');
    selectEl.appendChild(empty);
  }

  // ④ Ajouter les nouvelles options
  items.forEach(item => {
    const opt = new Option(item.label ?? item.toString, item.id ?? item.value);
    // Si multiple, marquer comme sélectionnée si dans prev
    if (isMultiple && Array.isArray(prev) && prev.includes(opt.value)) {
      opt.selected = true;
    }
    selectEl.appendChild(opt);
  });

  // ⑤ Si simple select, restaurer la valeur précédente
  if (!isMultiple) {
    selectEl.value = Array.from(selectEl.options).some(o => o.value == prev)
      ? prev
      : '';
  }
}



}
