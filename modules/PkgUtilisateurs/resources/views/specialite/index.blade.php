{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::specialite'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const specialiteCrud = new GappCrud({
        entity_name: 'specialite',
        indexUrl: '{{ route('specialites.index') }}', 
        createUrl: '{{ route('specialites.create') }}',
        editUrl: '{{ route('specialites.edit', ['specialite' => ':id']) }}',
        showUrl: '{{ route('specialites.show', ['specialite' => ':id']) }}',
        storeUrl: '{{ route('specialites.store') }}', 
        deleteUrl: '{{ route('specialites.destroy', ['specialite' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#specialitesTable', // Sélecteur du tableau HTML
        formSelector: '#specialiteForm',   // Sélecteur du formulaire
        modalSelector: '#specialiteModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::specialite.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::specialite.singular") }}',

    });
    specialiteCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::specialite._index')
@endsection
