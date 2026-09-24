document.addEventListener('alpine:init', () => {
    Alpine.store('qcm', {
        uas: window.QcmData.uas || [],
        activeUaIndex: 0,
        reponses: {},
        timeRemaining: window.QcmData.timeRemaining || 3600,
        
        next() {
            if (this.activeUaIndex < this.uas.length - 1) {
                this.activeUaIndex++;
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
