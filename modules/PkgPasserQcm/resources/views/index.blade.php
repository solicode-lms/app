@extends('layouts.passer-qcm')

@section('title', $realisationQcm->qcm->titre ?? 'Évaluation')
@section('qcm-title', $realisationQcm->qcm->titre ?? 'Évaluation')

@section('timer')
    @include('PkgPasserQcm::partials.timer')
@endsection

@section('content')
    <!-- Sidebar -->
    @include('PkgPasserQcm::partials.sidebar')

    <!-- Zone Centrale -->
    <section x-data="zoneCentraleComponent()" class="flex-grow flex flex-col max-w-3xl">
        
        <template x-if="$store.qcm.uas.length > 0">
            <div>
                <!-- Breadcrumb / Infos -->
                <div class="mb-6 flex justify-between items-end">
                    <div>
                        <span class="text-indigo-600 font-medium text-sm tracking-wide uppercase" x-text="`Unité d'Apprentissage ${activeUa.etape}`"></span>
                        <h2 class="text-2xl font-bold text-gray-900 mt-1" x-text="activeUa.ua_titre"></h2>
                    </div>
                    <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full" x-text="`${activeUa.questions.length} Questions`"></span>
                </div>

                <!-- Liste des questions pour cette UA -->
                <template x-for="question in activeUa.questions" :key="question.id">
                    <div>
                        @include('PkgPasserQcm::partials.question-card')
                    </div>
                </template>
            </div>
        </template>

        <template x-if="$store.qcm.uas.length === 0">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-gray-500">Aucune question trouvée pour ce QCM.</p>
            </div>
        </template>

        <!-- Footer Navigation (Next/Prev) -->
        <div class="mt-8 flex justify-between items-center" x-data>
            <button x-show="$store.qcm.activeUaIndex > 0" @click="$store.qcm.prev()" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors focus:ring-4 focus:ring-gray-100">
                Précédent
            </button>
            <div x-show="$store.qcm.activeUaIndex === 0"></div> <!-- Placeholder pour flex-between -->
            
            <button x-show="$store.qcm.activeUaIndex < $store.qcm.uas.length - 1" @click="$store.qcm.next()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm focus:ring-4 focus:ring-indigo-200 flex items-center gap-2">
                Suivant
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            
            <button x-show="$store.qcm.activeUaIndex === $store.qcm.uas.length - 1" class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors shadow-sm focus:ring-4 focus:ring-emerald-200 flex items-center gap-2">
                Soumettre le QCM
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // 1. Définition du Store Global
        Alpine.store('qcm', {
            uas: @json($dataUaGrouped),
            activeUaIndex: 0,
            reponses: {},
            timeRemaining: {{ ($realisationQcm->qcm->duree_minutes ?? 60) * 60 }},
            
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

        // 2. Composants isolés
        Alpine.data('timerComponent', () => ({
            timerInterval: null,
            init() {
                this.timerInterval = setInterval(() => {
                    if (this.$store.qcm.timeRemaining > 0) {
                        this.$store.qcm.timeRemaining--;
                    } else {
                        clearInterval(this.timerInterval);
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

        Alpine.data('sidebarComponent', () => ({}));
        
        Alpine.data('zoneCentraleComponent', () => ({
            get activeUa() {
                return this.$store.qcm.uas[this.$store.qcm.activeUaIndex];
            }
        }));

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
</script>
@endpush
