document.addEventListener('alpine:init', () => {
    Alpine.data('timerComponent', () => ({
        timerInterval: null,
        
        init() {
            this.timerInterval = setInterval(() => {
                if (this.$store.qcm.timeRemaining > 0) {
                    this.$store.qcm.timeRemaining--;
                } else {
                    clearInterval(this.timerInterval);
                    alert("Le temps imparti est écoulé. Le QCM va être soumis automatiquement.");
                    this.$store.qcm.submit();
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
