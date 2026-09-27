document.addEventListener('alpine:init', () => {
    // Composant minimaliste pour la zone centrale
    Alpine.data('zoneCentraleComponent', () => ({
        init() {
            // À chaque fois que l'UA change (Next, Prev, ou clic dans la sidebar)
            this.$watch('$store.qcm.activeUaIndex', () => {
                this.$nextTick(() => {
                    this.scrollToIdealPosition();
                });
            });
        },
        
        get activeUa() {
            return this.$store.qcm.uas[this.$store.qcm.activeUaIndex];
        },
        
        scrollToIdealPosition() {
            const currentUa = this.activeUa;
            if (!currentUa || !currentUa.questions) return;
            
            let targetId = null;
            // Cherche la première question non répondue
            for (let q of currentUa.questions) {
                const ans = this.$store.qcm.reponses[q.id];
                const isMultiple = (q.type && q.type.toLowerCase() === 'choix multiple');
                const isAnswered = isMultiple 
                    ? (Array.isArray(ans) && ans.length > 0)
                    : (ans !== undefined && ans !== null);
                
                if (!isAnswered) {
                    targetId = 'question-' + q.id;
                    break;
                }
            }
            
            if (targetId) {
                const el = document.getElementById(targetId);
                if (el) {
                    // On scrolle avec un décalage si possible (center) pour bien voir la question
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
            }
            // Si toutes les questions ont une réponse, on scrolle tout en haut
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }));
});
