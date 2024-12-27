{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::appreciation'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const appreciationCrud = new GappCrud({
        entity_name: 'appreciation',
        indexUrl: '{{ route('appreciations.index') }}', 
        createUrl: '{{ route('appreciations.create') }}',
        editUrl: '{{ route('appreciations.edit', ['appreciation' => ':id']) }}',
        showUrl: '{{ route('appreciations.show', ['appreciation' => ':id']) }}',
        storeUrl: '{{ route('appreciations.store') }}', 
        deleteUrl: '{{ route('appreciations.destroy', ['appreciation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#appreciationsTable', // Sélecteur du tableau HTML
        formSelector: '#appreciationForm',   // Sélecteur du formulaire
        modalSelector: '#appreciationModal'  // Sélecteur du modal
    });
    appreciationCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::appreciation._index')
@endsection
