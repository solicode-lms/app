document.addEventListener('alpine:init', () => {
    Alpine.data('timerComponent', () => ({
        timerInterval: null,
        
        init() {
            if (this.$store.qcm.timeRemaining === null) return;
            
            // On calcule l'heure de fin absolue pour le client
            this.targetTime = Date.now() + (this.$store.qcm.timeRemaining * 1000);
            
            this.timerInterval = setInterval(() => {
                const now = Date.now();
                const remaining = (this.targetTime - now) / 1000;
                
                if (remaining > 0) {
                    this.$store.qcm.timeRemaining = remaining;
                } else {
                    this.$store.qcm.timeRemaining = 0;
                    clearInterval(this.timerInterval);
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Temps écoulé !', 'Le QCM va être soumis automatiquement.', 'info');
                    } else {
                        alert("Le temps imparti est écoulé. Le QCM va être soumis automatiquement.");
                    }
                    
                    this.$store.qcm.submit(true);
                }
            }, 1000);
        },
        
        get formattedTime() {
            if (this.$store.qcm.timeRemaining === null) return 'Illimité';
            
            const totalSeconds = Math.floor(this.$store.qcm.timeRemaining);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
    }));
});
