// /components et /utils existent déjà dans ton projet
import { AjaxErrorHandler } from '../components/AjaxErrorHandler';
import { Action } from './Action';
import EventUtil from '../utils/EventUtil';
import { NotificationHandler } from '../components/NotificationHandler';

export class CrudAction extends Action {

  /**
   * @param {Object} config
   * @param {Object} tableUI
   * @param {string|null} containerSelector - par défaut config.crudSelector
   */
  constructor(config, tableUI, containerSelector = null) {
    super(config);
    this.config = config;
    this.tableUI = tableUI;
    this.containerSelector = containerSelector || this.config.crudSelector;

    // Message de succès générique (les enfants peuvent le surcharger)
    this.successMessage = 'Opération effectuée avec succès.';
  }

     /**
     * Lance le traitement différé (sans attendre la réponse) puis démarre le polling.
     *
     * @param {string} token - Token du traitement à surveiller
     * @param {object|null} loader - Loader avec .showNomBloquante() et .hide()
     * @param {function|null} onDoneCallback - Fonction appelée après traitement terminé
     */ 
    pollTraitementStatus(token, onDoneCallback = null) {

        let loader = this.loader_traitement;
        loader.showNomBloquante("⏳ Traitement lancé...");
        let error = false;


        $.get('/admin/traitement/start')
        .done(() => {
        })
        .fail((xhr) => {
            // ❌ Une erreur est survenue côté serveur
            const message = xhr.responseJSON?.message || 'Erreur lors du démarrage du traitement.';
            NotificationHandler.showError('❌ ' + message);
            loader?.hide();
            error = true;
            this.tableUI.entityLoader.loadEntities();
        });


        // ⏱️ Démarrer le polling
        const poll = () => {
            $.get('/admin/traitement/status/' + token)
                .done((res) => {
                    const status = res.status;
                    const progress = res.progress ?? 0;
                    const messageError = res.messageError ?? "";

                    if (status === 'done') {
                        if (loader) loader.hide();
                        NotificationHandler.showSuccess('✅ Traitement terminé.');
                        this.tableUI.entityLoader.loadEntities();
                        if (typeof onDoneCallback === 'function') {
                            onDoneCallback();
                        }

                    } else if (status.startsWith('error')) {
                        if (loader) loader.hide();
                        NotificationHandler.showError('❌ Erreur traitement : ' + messageError);
                        this.tableUI.entityLoader.loadEntities();
                    } else {
                        if(!error){
                            loader?.showNomBloquante(`⏳ Traitement en cours... ${progress}%`);
                            setTimeout(poll, 2000);
                        }
                    }
                })
                .fail(() => {
                    if (loader) loader.hide();
                    NotificationHandler.showError('❌ Erreur réseau pendant le polling.');
                    this.tableUI.entityLoader.loadEntities();
                });
        };

        poll(); // 🚀 Lancer immédiatement la boucle de polling
    }

}