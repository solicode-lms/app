{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::competence'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const competenceCrud = new GappCrud({
        entity_name: 'competence',
        indexUrl: '{{ route('competences.index') }}', 
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit', ['competence' => ':id']) }}',
        showUrl: '{{ route('competences.show', ['competence' => ':id']) }}',
        storeUrl: '{{ route('competences.store') }}', 
        deleteUrl: '{{ route('competences.destroy', ['competence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#competencesTable', // Sélecteur du tableau HTML
        formSelector: '#competenceForm',   // Sélecteur du formulaire
        modalSelector: '#competenceModal'  // Sélecteur du modal
    });
    competenceCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::competence._index')
@endsection
