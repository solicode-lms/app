document.addEventListener('alpine:init', () => {
    Alpine.data('timerComponent', () => ({
        timerInterval: null,
        
        init() {
            this.timerInterval = setInterval(() => {
                if (this.$store.qcm.timeRemaining > 0) {
                    this.$store.qcm.timeRemaining--;
                } else {
                    clearInterval(this.timerInterval);
                    // Logique de soumission automatique ici si nécessaire
                }
            }, 1000);
        },
        
        get formattedTime() {
            const totalSeconds = this.$store.qcm.timeRemaining;
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
    }));
});
