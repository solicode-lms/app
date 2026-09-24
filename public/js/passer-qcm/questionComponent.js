document.addEventListener('alpine:init', () => {
    Alpine.data('questionComponent', (question) => ({
        question: question,
        
        get isMultiple() {
            return this.question.type && this.question.type.toLowerCase() === 'choix multiple';
        },
        
        isSelected(propositionId) {
            const ans = this.$store.qcm.reponses[this.question.id];
            if (this.isMultiple) {
                return Array.isArray(ans) && ans.includes(propositionId);
            }
            return ans == propositionId;
        },
        
        toggle(propositionId) {
            this.$store.qcm.setReponse(this.question.id, propositionId, this.isMultiple);
        }
    }));
});
