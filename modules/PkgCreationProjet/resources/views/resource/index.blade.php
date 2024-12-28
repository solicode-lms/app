{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::resource'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const resourceCrud = new GappCrud({
        entity_name: 'resource',
        indexUrl: '{{ route('resources.index') }}', 
        createUrl: '{{ route('resources.create') }}',
        editUrl: '{{ route('resources.edit', ['resource' => ':id']) }}',
        showUrl: '{{ route('resources.show', ['resource' => ':id']) }}',
        storeUrl: '{{ route('resources.store') }}', 
        deleteUrl: '{{ route('resources.destroy', ['resource' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#resourcesTable', // Sélecteur du tableau HTML
        formSelector: '#resourceForm',   // Sélecteur du formulaire
        modalSelector: '#resourceModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',

    });
    resourceCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCreationProjet::resource._index')
@endsection
