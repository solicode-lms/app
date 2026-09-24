@extends('layouts.passer-qcm')

@section('title', $realisationQcm->qcm->titre ?? 'Évaluation')
@section('qcm-title', $realisationQcm->qcm->titre ?? 'Évaluation')

@section('content')
    <div class="w-full bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-indigo-700">Sprint 3 : Données Chargées avec Succès</h2>
        
        <p class="mb-4 text-gray-600">
            Voici un aperçu de la structure des données générées par le contrôleur (Questions groupées par UA). 
            Cette structure sera injectée dans Alpine.js lors du Sprint 5.
        </p>

        <pre class="bg-gray-800 text-green-400 p-4 rounded-lg text-sm overflow-x-auto">@json($dataUaGrouped, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)</pre>
    </div>
@endsection
