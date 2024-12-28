{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysController'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const sysControllerCrud = new GappCrud({
        entity_name: 'sysController',
        indexUrl: '{{ route('sysControllers.index') }}', 
        createUrl: '{{ route('sysControllers.create') }}',
        editUrl: '{{ route('sysControllers.edit', ['sysController' => ':id']) }}',
        showUrl: '{{ route('sysControllers.show', ['sysController' => ':id']) }}',
        storeUrl: '{{ route('sysControllers.store') }}', 
        deleteUrl: '{{ route('sysControllers.destroy', ['sysController' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#sysControllersTable', // Sélecteur du tableau HTML
        formSelector: '#sysControllerForm',   // Sélecteur du formulaire
        modalSelector: '#sysControllerModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',

    });
    sysControllerCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('Core::sysController._index')
@endsection
