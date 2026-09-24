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
    // 1. Initialisation des variables PHP dans l'objet global
    window.QcmData = {
        uas: @json($dataUaGrouped),
        timeRemaining: {{ ($realisationQcm->qcm->duree_minutes ?? 60) * 60 }}
    };
</script>

<!-- 2. Chargement des composants Alpine extraits dans des fichiers séparés -->
<script src="{{ asset('js/passer-qcm/store.js') }}"></script>
<script src="{{ asset('js/passer-qcm/timerComponent.js') }}"></script>
<script src="{{ asset('js/passer-qcm/questionComponent.js') }}"></script>
@endpush
