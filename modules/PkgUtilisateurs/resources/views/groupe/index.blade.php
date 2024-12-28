{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::groupe'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const groupeCrud = new GappCrud({
        entity_name: 'groupe',
        indexUrl: '{{ route('groupes.index') }}', 
        createUrl: '{{ route('groupes.create') }}',
        editUrl: '{{ route('groupes.edit', ['groupe' => ':id']) }}',
        showUrl: '{{ route('groupes.show', ['groupe' => ':id']) }}',
        storeUrl: '{{ route('groupes.store') }}', 
        deleteUrl: '{{ route('groupes.destroy', ['groupe' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#groupesTable', // Sélecteur du tableau HTML
        formSelector: '#groupeForm',   // Sélecteur du formulaire
        modalSelector: '#groupeModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::groupe.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::groupe.singular") }}',

    });
    groupeCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::groupe._index')
@endsection
