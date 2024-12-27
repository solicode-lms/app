{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::formateur'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const formateurCrud = new GappCrud({
        entity_name: 'formateur',
        indexUrl: '{{ route('formateurs.index') }}', 
        createUrl: '{{ route('formateurs.create') }}',
        editUrl: '{{ route('formateurs.edit', ['formateur' => ':id']) }}',
        showUrl: '{{ route('formateurs.show', ['formateur' => ':id']) }}',
        storeUrl: '{{ route('formateurs.store') }}', 
        deleteUrl: '{{ route('formateurs.destroy', ['formateur' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#formateursTable', // Sélecteur du tableau HTML
        formSelector: '#formateurForm',   // Sélecteur du formulaire
        modalSelector: '#formateurModal'  // Sélecteur du modal
    });
    formateurCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::formateur._index')
@endsection
