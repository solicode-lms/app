@extends('layouts.passer-qcm')

@section('title', $realisationQcm->qcm->titre ?? 'Évaluation')
@section('qcm-title', $realisationQcm->qcm->titre ?? 'Évaluation')

@section('timer')
    @include('PkgPasserQcm::partials.timer')
@endsection

@section('actions-top')
    <div class="flex items-center gap-2" x-data>
        <button x-show="$store.qcm.activeUaIndex > 0" 
                @click.throttle.500ms="$store.qcm.prev()" 
                :disabled="$store.qcm.isLoading"
                class="px-3 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors focus:ring-2 focus:ring-gray-100 disabled:opacity-50 disabled:cursor-not-allowed" 
                title="Précédent">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        
        <button x-show="$store.qcm.activeUaIndex < $store.qcm.uas.length - 1" 
                @click.throttle.500ms="$store.qcm.next()" 
                :disabled="$store.qcm.isLoading"
                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:ring-2 focus:ring-indigo-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <span>Suivant</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        
        <button x-show="$store.qcm.activeUaIndex === $store.qcm.uas.length - 1" 
                @click.throttle.1000ms="$store.qcm.submit()" 
                :disabled="$store.qcm.isLoading"
                class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm focus:ring-2 focus:ring-emerald-200 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <span>Soumettre</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </button>
    </div>
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



        <!-- Overlay de chargement -->
        <div x-show="$store.qcm.isLoading" 
             style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-indigo-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 font-medium">Soumission en cours...</span>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // 1. Initialisation des variables PHP dans l'objet global
    window.QcmData = {
        realisationId: {{ $realisationQcm->id }},
        uas: @json($dataUaGrouped),
        timeRemaining: {{ is_null($timeRemaining) ? 'null' : $timeRemaining }},
        csrfToken: '{{ csrf_token() }}',
        saveUrl: '{{ route('passerQcm.save-incremental', $realisationQcm->id) }}',
        submitUrl: '{{ route('passerQcm.submit', $realisationQcm->id) }}',
        redirectUrl: '{{ route('realisationQcms.index') }}'
    };
</script>

<!-- 2. Chargement du point d'entrée principal des composants Alpine (ES Modules) -->
<script type="module" src="{{ asset('js/passer-qcm/app.js') }}?v={{ time() }}"></script>
@endpush
