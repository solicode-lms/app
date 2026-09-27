import { QUESTION_TYPES } from '../constants.js';

export default function registerQcmStore(Alpine) {
    // 1. Reconstruire les réponses depuis le serveur
    const serverReponses = {};
    if (window.QcmData && window.QcmData.uas) {
        window.QcmData.uas.forEach(ua => {
            if(ua.questions) {
                ua.questions.forEach(q => {
                    if (q.selected_propositions && q.selected_propositions.length > 0) {
                        if (q.type && q.type.toLowerCase() === QUESTION_TYPES.CHOIX_MULTIPLE) {
                            serverReponses[q.id] = q.selected_propositions;
                        } else {
                            serverReponses[q.id] = q.selected_propositions[0];
                        }
                    }
                });
            }
        });
    }

    // 2. Charger le LocalStorage
    const realId = window.QcmData.realisationId || '0';
    const lsKey = 'qcm_reponses_' + realId;
    let localReponses = {};
    try {
        const stored = localStorage.getItem(lsKey);
        if (stored) localReponses = JSON.parse(stored);
    } catch(e) {
        console.error("Erreur lors de la lecture du LocalStorage:", e);
    }

    // 3. Fusionner (LocalStorage prioritaire pour éviter la perte des clics récents non envoyés)
    const mergedReponses = { ...serverReponses, ...localReponses };

    Alpine.store('qcm', {
        uas: window.QcmData.uas || [],
        activeUaIndex: 0,
        reponses: mergedReponses,
        timeRemaining: (window.QcmData && window.QcmData.timeRemaining !== undefined) ? window.QcmData.timeRemaining : null,
        lsKey: lsKey,
        isLoading: false,
        isSaving: false, // Nouvel état pour la sauvegarde incrémentale en arrière-plan
        
        persistToLocal() {
            try {
                localStorage.setItem(this.lsKey, JSON.stringify(Alpine.raw(this.reponses)));
            } catch(e) {
                console.error("Erreur lors de la sauvegarde dans le LocalStorage:", e);
            }
        },
        
        async saveCurrentUa() {
            const currentUa = this.uas[this.activeUaIndex];
            if (!currentUa || !currentUa.questions) return;
            
            // On ne récupère que les réponses de la UA courante
            const reponsesToSave = {};
            currentUa.questions.forEach(q => {
                if (this.reponses[q.id] !== undefined) {
                    reponsesToSave[q.id] = this.reponses[q.id];
                }
            });
            
            this.isSaving = true;
            try {
                await fetch(window.QcmData.saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.QcmData.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ reponses: reponsesToSave })
                });
            } catch (e) {
                console.error("Erreur lors de la sauvegarde incrémentale", e);
            } finally {
                this.isSaving = false;
            }
        },

        next() {
            this.saveCurrentUa(); // Fire and forget (asynchrone)
            if (this.activeUaIndex < this.uas.length - 1) {
                this.activeUaIndex++;
            }
        },
        
        getUnansweredCount() {
            let count = 0;
            this.uas.forEach(ua => {
                if (ua.questions) {
                    ua.questions.forEach(q => {
                        const ans = this.reponses[q.id];
                        const isMultiple = (q.type && q.type.toLowerCase() === QUESTION_TYPES.CHOIX_MULTIPLE);
                        const isAnswered = isMultiple 
                            ? (Array.isArray(ans) && ans.length > 0)
                            : (ans !== undefined && ans !== null);
                        
                        if (!isAnswered) count++;
                    });
                }
            });
            return count;
        },
        
        async submit(force = false) {
            if (this.isLoading) return;
            
            await this.saveCurrentUa();
            
            if (!force && typeof Swal !== 'undefined') {
                const unanswered = this.getUnansweredCount();
                let message = "Êtes-vous sûr de vouloir soumettre ce QCM ?";
                if (unanswered > 0) {
                    message = `Il vous reste ${unanswered} question(s) sans réponse. Voulez-vous vraiment terminer ?`;
                }
                
                const result = await Swal.fire({
                    title: 'Confirmer la soumission',
                    text: message,
                    icon: unanswered > 0 ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Oui, soumettre',
                    cancelButtonText: 'Annuler'
                });
                
                if (!result.isConfirmed) return;
            }
            
            this.isLoading = true;
            try {
                const response = await fetch(window.QcmData.submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.QcmData.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ reponses: this.reponses })
                });
                
                if (response.ok) {
                    localStorage.removeItem(this.lsKey); // Nettoyage après succès
                    window.location.href = window.QcmData.redirectUrl;
                } else {
                    this.isLoading = false;
                }
            } catch (e) {
                console.error("Erreur lors de la soumission", e);
                this.isLoading = false;
            }
        },
        
        prev() {
            this.saveCurrentUa(); // Fire and forget (asynchrone)
            if (this.activeUaIndex > 0) {
                this.activeUaIndex--;
            }
        },
        
        isUaCompleted(index) {
            const ua = this.uas[index];
            if (!ua || !ua.questions) return true;
            
            return ua.questions.every(q => {
                const ans = this.reponses[q.id];
                if (q.type && q.type.toLowerCase() === QUESTION_TYPES.CHOIX_MULTIPLE) {
                    return Array.isArray(ans) && ans.length > 0;
                }
                return ans !== undefined && ans !== null;
            });
        },
        
        setReponse(questionId, propositionId, isMultiple) {
            if (isMultiple) {
                if (!this.reponses[questionId]) {
                    this.reponses[questionId] = [];
                }
                const idx = this.reponses[questionId].indexOf(propositionId);
                if (idx > -1) {
                    this.reponses[questionId].splice(idx, 1);
                } else {
                    this.reponses[questionId].push(propositionId);
                }
            } else {
                this.reponses[questionId] = propositionId;
            }
            this.persistToLocal(); // Sauvegarde instantanée
        }
    });
}
