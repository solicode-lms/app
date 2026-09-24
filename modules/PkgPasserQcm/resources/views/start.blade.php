@extends('layouts.passer-qcm')

@section('title', 'Démarrage - ' . ($realisationQcm->qcm->titre ?? 'Évaluation'))
@section('qcm-title', $realisationQcm->qcm->titre ?? 'Évaluation')

@section('content')
<div class="flex-grow flex items-center justify-center max-w-4xl mx-auto w-full py-12">
    <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100 w-full text-center">
        
        <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
        </div>
        
        <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $realisationQcm->qcm->titre }}</h2>
        <p class="text-gray-500 mb-8 max-w-2xl mx-auto">
            Vous êtes sur le point de commencer cette évaluation. Veuillez lire attentivement les informations ci-dessous avant de démarrer le chronomètre.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10 text-left">
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Durée accordée</h4>
                    <p class="text-xl font-bold text-gray-900">{{ $realisationQcm->qcm->duree_minutes ?? 60 }} Minutes</p>
                </div>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total des questions</h4>
                    <p class="text-xl font-bold text-gray-900">{{ $nbQuestions }} Questions</p>
                </div>
            </div>
        </div>

        <form action="{{ route('passerQcm.start', $realisationQcm->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm focus:ring-4 focus:ring-indigo-200 text-lg flex items-center justify-center gap-3 mx-auto w-full md:w-auto">
                Commencer l'évaluation
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>
</div>
@endsection
