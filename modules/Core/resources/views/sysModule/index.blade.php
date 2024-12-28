{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysModule'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const sysModuleCrud = new GappCrud({
        entity_name: 'sysModule',
        indexUrl: '{{ route('sysModules.index') }}', 
        createUrl: '{{ route('sysModules.create') }}',
        editUrl: '{{ route('sysModules.edit', ['sysModule' => ':id']) }}',
        showUrl: '{{ route('sysModules.show', ['sysModule' => ':id']) }}',
        storeUrl: '{{ route('sysModules.store') }}', 
        deleteUrl: '{{ route('sysModules.destroy', ['sysModule' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#sysModulesTable', // Sélecteur du tableau HTML
        formSelector: '#sysModuleForm',   // Sélecteur du formulaire
        modalSelector: '#sysModuleModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',

    });
    sysModuleCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('Core::sysModule._index')
@endsection
