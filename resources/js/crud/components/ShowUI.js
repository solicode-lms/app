import { LoadingIndicator } from "./LoadingIndicator";
import { ViewStateService } from './ViewStateService';
import DynamicFieldVisibilityTreatment from "../treatments/form/DynamicFieldVisibilityTreatment";
import { CodeJar } from 'codejar';
import EventUtil from './../utils/EventUtil';
import Prism from 'prismjs';
import 'prismjs/themes/prism.css';
import 'prismjs/components/prism-json';
import InitUIManagers from "../InitUIManagers";
import flatpickr from 'flatpickr';
import { French } from 'flatpickr/dist/l10n/fr.js';
import { NotificationHandler } from "./NotificationHandler";
import DynamicDropdownTreatment from "../treatments/global/DynamicDropdownTreatment";
import { EditAction } from "../actions/EditAction";

/**
 * Classe ShowUI pour gérer l'affichage en lecture seule d'un enregistrement.
 */
export class ShowUI {
  /**
   * @param {Object} config - Configuration contenant viewStateService, selectors, etc.
   * @param {IndexUI} indexUI - Instance d'IndexUI pour accéder aux composants modaux ou listes.
   * @param {string} containerSelector - Sélecteur CSS du conteneur de la vue Show.
   */
  constructor(config, indexUI, containerSelector = '') {
    this.config = config;
    this.indexUI = indexUI;
    this.containerSelector = containerSelector || this.config.showContainerSelector;
    this.entityEditor = new EditAction(config,this.indexUI.tableUI,this.containerSelector);
    this.viewStateService = this.config.viewStateService;
    this.loader = new LoadingIndicator(this.containerSelector);
  }

  /**
   * Initialise la vue Show : chargement, décorations, traitements dynamiques.
   */
  init() {

    InitUIManagers.init();
    this.loader.init();
    this.handleCardFooter();
    this.handleCancelButton();
    this.entityEditor.init();

    this.initTooltip();
    this.initializeDate();
    this.initCodeJar();

    // Traitements dynamiques de visibilité
    if (window.dynamicFieldVisibilityTreatments) {
      new DynamicFieldVisibilityTreatment(window.dynamicFieldVisibilityTreatments)
        .initialize();
    }

    // Dropdown dynamiques (lecture seule)
    document.querySelectorAll(this.containerSelector + " [data-target-dynamic-dropdown]")
      .forEach(el => new DynamicDropdownTreatment(el, this.config));

    // Afficher les états du viewState dans le debug
    if (this.config.isDebug) {
      this.highlightStateFields();
    }
  }

  /**
   * Convertit les card-footer en modal-footer pour cohérence visuelle.
   */
  handleCardFooter() {
    const ctx = `${this.containerSelector}`;
    $(ctx)
      .find('.card-footer')
      .removeClass('card-footer')
      .addClass('modal-footer');
  }

  /**
   * Initialisation des tooltips Bootstrap.
   */
  initTooltip() {
    $('[data-toggle="tooltip"]', this.containerSelector).tooltip();
  }

  /**
   * Applique Flatpickr sur les champs datetime en lecture seule.
   */
  initializeDate() {
    flatpickr(this.containerSelector + ' .datetimepicker', {
      enableTime: true,
      dateFormat: 'Y-m-d H:i',
      time_24hr: true,
      locale: French,
      allowInput: false,
      weekNumbers: true,
      clickOpens: false,
    });
  }

  /**
   * Initialise CodeJar pour l'affichage de JSON syntaxé.
   */
  initCodeJar() {
    const editors = document.querySelectorAll(this.containerSelector + ' .code-editor');
    editors.forEach(editor => {
      const highlight = (ed) => {
        try {
          const formatted = JSON.stringify(JSON.parse(ed.textContent), null, 2);
          ed.innerHTML = Prism.highlight(formatted, Prism.languages.json, 'json');
        } catch {
          ed.innerHTML = Prism.highlight(ed.textContent, Prism.languages.json, 'json');
        }
      };
      const jar = CodeJar(editor, highlight);
      // désactiver la mise à jour
      jar.onUpdate = () => {};
      // charger contenu initial
      highlight(editor);
    });
  }

  /**
   * En mode debug, surligne les champs affichant l'état.
   */
  highlightStateFields() {
    const state = this.viewStateService.getScopeShowVariables();
    Object.keys(state).forEach(key => {
      const el = document.querySelector(`${this.containerSelector} #${key}`);
      if (el) {
        el.parentElement.style.backgroundColor = 'lightyellow';
      }
    });
  }

  /**
   * Affiche une notification d'erreur ou d'information.
   * @param {string} message
   * @param {string} type - 'error'|'success'|'info'
   */
  showNotification(message, type = 'info') {
    NotificationHandler[type === 'error' ? 'showError' : 'show'](message);
  }

  handleCancelButton() {

          EventUtil.bindEvent('click', `${this.containerSelector} .form-cancel-button`, (e) => {
              e.preventDefault();
              this.indexUI.modalUI.close();
          });
          
  }
}
