{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::filiere'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const filiereCrud = new GappCrud({
        entity_name: 'filiere',
        indexUrl: '{{ route('filieres.index') }}', 
        createUrl: '{{ route('filieres.create') }}',
        editUrl: '{{ route('filieres.edit', ['filiere' => ':id']) }}',
        showUrl: '{{ route('filieres.show', ['filiere' => ':id']) }}',
        storeUrl: '{{ route('filieres.store') }}', 
        deleteUrl: '{{ route('filieres.destroy', ['filiere' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#filieresTable', // Sélecteur du tableau HTML
        formSelector: '#filiereForm',   // Sélecteur du formulaire
        modalSelector: '#filiereModal'  // Sélecteur du modal
    });
    filiereCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::filiere._index')
@endsection
