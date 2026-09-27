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

    @include('PkgPasserQcm::partials.zone-centrale')
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
