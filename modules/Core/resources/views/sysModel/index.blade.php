{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysModel'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const sysModelCrud = new GappCrud({
        entity_name: 'sysModel',
        indexUrl: '{{ route('sysModels.index') }}', 
        createUrl: '{{ route('sysModels.create') }}',
        editUrl: '{{ route('sysModels.edit', ['sysModel' => ':id']) }}',
        showUrl: '{{ route('sysModels.show', ['sysModel' => ':id']) }}',
        storeUrl: '{{ route('sysModels.store') }}', 
        deleteUrl: '{{ route('sysModels.destroy', ['sysModel' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#sysModelsTable', // Sélecteur du tableau HTML
        formSelector: '#sysModelForm',   // Sélecteur du formulaire
        modalSelector: '#sysModelModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',

    });
    sysModelCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('Core::sysModel._index')
@endsection
