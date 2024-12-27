{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::niveauCompetence'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const niveauCompetenceCrud = new GappCrud({
        entity_name: 'niveauCompetence',
        indexUrl: '{{ route('niveauCompetences.index') }}', 
        createUrl: '{{ route('niveauCompetences.create') }}',
        editUrl: '{{ route('niveauCompetences.edit', ['niveauCompetence' => ':id']) }}',
        showUrl: '{{ route('niveauCompetences.show', ['niveauCompetence' => ':id']) }}',
        storeUrl: '{{ route('niveauCompetences.store') }}', 
        deleteUrl: '{{ route('niveauCompetences.destroy', ['niveauCompetence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#niveauCompetencesTable', // Sélecteur du tableau HTML
        formSelector: '#niveauCompetenceForm',   // Sélecteur du formulaire
        modalSelector: '#niveauCompetenceModal'  // Sélecteur du modal
    });
    niveauCompetenceCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::niveauCompetence._index')
@endsection
