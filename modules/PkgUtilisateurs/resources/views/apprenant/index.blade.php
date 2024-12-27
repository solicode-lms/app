{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::apprenant'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const apprenantCrud = new GappCrud({
        entity_name: 'apprenant',
        indexUrl: '{{ route('apprenants.index') }}', 
        createUrl: '{{ route('apprenants.create') }}',
        editUrl: '{{ route('apprenants.edit', ['apprenant' => ':id']) }}',
        showUrl: '{{ route('apprenants.show', ['apprenant' => ':id']) }}',
        storeUrl: '{{ route('apprenants.store') }}', 
        deleteUrl: '{{ route('apprenants.destroy', ['apprenant' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#apprenantsTable', // Sélecteur du tableau HTML
        formSelector: '#apprenantForm',   // Sélecteur du formulaire
        modalSelector: '#apprenantModal'  // Sélecteur du modal
    });
    apprenantCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::apprenant._index')
@endsection
