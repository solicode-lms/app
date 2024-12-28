{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgWidgets::widgetOperation'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const widgetOperationCrud = new GappCrud({
        entity_name: 'widgetOperation',
        indexUrl: '{{ route('widgetOperations.index') }}', 
        createUrl: '{{ route('widgetOperations.create') }}',
        editUrl: '{{ route('widgetOperations.edit', ['widgetOperation' => ':id']) }}',
        showUrl: '{{ route('widgetOperations.show', ['widgetOperation' => ':id']) }}',
        storeUrl: '{{ route('widgetOperations.store') }}', 
        deleteUrl: '{{ route('widgetOperations.destroy', ['widgetOperation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#widgetOperationsTable', // Sélecteur du tableau HTML
        formSelector: '#widgetOperationForm',   // Sélecteur du formulaire
        modalSelector: '#widgetOperationModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',

    });
    widgetOperationCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgWidgets::widgetOperation._index')
@endsection
