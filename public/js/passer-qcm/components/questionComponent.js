import { QUESTION_TYPES } from '../constants.js';

export default function registerQuestionComponent(Alpine) {
    Alpine.data('questionComponent', (question) => ({
        question: question,
        
        get isMultiple() {
            return this.question.type && this.question.type.toLowerCase() === QUESTION_TYPES.CHOIX_MULTIPLE;
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
}
