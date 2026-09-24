document.addEventListener('alpine:init', () => {
    
    // 1. Reconstruire les réponses depuis le serveur
    const serverReponses = {};
    if (window.QcmData && window.QcmData.uas) {
        window.QcmData.uas.forEach(ua => {
            if(ua.questions) {
                ua.questions.forEach(q => {
                    if (q.selected_propositions && q.selected_propositions.length > 0) {
                        if (q.type && q.type.toLowerCase() === 'choix multiple') {
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
    } catch(e) {}

    // 3. Fusionner (LocalStorage prioritaire pour éviter la perte des clics récents non envoyés)
    const mergedReponses = { ...serverReponses, ...localReponses };

    Alpine.store('qcm', {
        uas: window.QcmData.uas || [],
        activeUaIndex: 0,
        reponses: mergedReponses,
        timeRemaining: window.QcmData.timeRemaining || 3600,
        lsKey: lsKey,
        
        persistToLocal() {
            try {
                localStorage.setItem(this.lsKey, JSON.stringify(Alpine.raw(this.reponses)));
            } catch(e) {}
        },
        
        async saveCurrentUa() {
            const currentUa = this.uas[this.activeUaIndex];
            if (!currentUa || !currentUa.questions) return;
            
            // On ne récupère que les réponses de la page courante
            const reponsesToSave = {};
            currentUa.questions.forEach(q => {
                if (this.reponses[q.id] !== undefined) {
                    reponsesToSave[q.id] = this.reponses[q.id];
                }
            });
            
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
            }
        },

        async next() {
            await this.saveCurrentUa();
            if (this.activeUaIndex < this.uas.length - 1) {
                this.activeUaIndex++;
            }
        },
        
        async submit() {
            await this.saveCurrentUa();
            
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
                }
            } catch (e) {
                console.error("Erreur lors de la soumission", e);
            }
        },
        
        prev() {
            if (this.activeUaIndex > 0) {
                this.activeUaIndex--;
            }
        },
        
        isUaCompleted(index) {
            const ua = this.uas[index];
            if (!ua || !ua.questions) return true;
            
            return ua.questions.every(q => {
                const ans = this.reponses[q.id];
                if (q.type && q.type.toLowerCase() === 'choix multiple') {
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

    // Composant minimaliste pour la zone centrale si besoin de getters spécifiques
    Alpine.data('zoneCentraleComponent', () => ({
        get activeUa() {
            return this.$store.qcm.uas[this.$store.qcm.activeUaIndex];
        }
    }));
    
    // Composant minimaliste pour la sidebar
    Alpine.data('sidebarComponent', () => ({}));
});
