{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::nationalite'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const nationaliteCrud = new GappCrud({
        entity_name: 'nationalite',
        indexUrl: '{{ route('nationalites.index') }}', 
        createUrl: '{{ route('nationalites.create') }}',
        editUrl: '{{ route('nationalites.edit', ['nationalite' => ':id']) }}',
        showUrl: '{{ route('nationalites.show', ['nationalite' => ':id']) }}',
        storeUrl: '{{ route('nationalites.store') }}', 
        deleteUrl: '{{ route('nationalites.destroy', ['nationalite' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#nationalitesTable', // Sélecteur du tableau HTML
        formSelector: '#nationaliteForm',   // Sélecteur du formulaire
        modalSelector: '#nationaliteModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::nationalite.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::nationalite.singular") }}',

    });
    nationaliteCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::nationalite._index')
@endsection
