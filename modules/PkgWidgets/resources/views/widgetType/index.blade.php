{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgWidgets::widgetType'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const widgetTypeCrud = new GappCrud({
        entity_name: 'widgetType',
        indexUrl: '{{ route('widgetTypes.index') }}', 
        createUrl: '{{ route('widgetTypes.create') }}',
        editUrl: '{{ route('widgetTypes.edit', ['widgetType' => ':id']) }}',
        showUrl: '{{ route('widgetTypes.show', ['widgetType' => ':id']) }}',
        storeUrl: '{{ route('widgetTypes.store') }}', 
        deleteUrl: '{{ route('widgetTypes.destroy', ['widgetType' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#widgetTypesTable', // Sélecteur du tableau HTML
        formSelector: '#widgetTypeForm',   // Sélecteur du formulaire
        modalSelector: '#widgetTypeModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',

    });
    widgetTypeCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgWidgets::widgetType._index')
@endsection
