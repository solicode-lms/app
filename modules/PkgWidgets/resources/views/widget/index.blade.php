{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgWidgets::widget'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const widgetCrud = new GappCrud({
        entity_name: 'widget',
        indexUrl: '{{ route('widgets.index') }}', 
        createUrl: '{{ route('widgets.create') }}',
        editUrl: '{{ route('widgets.edit', ['widget' => ':id']) }}',
        showUrl: '{{ route('widgets.show', ['widget' => ':id']) }}',
        storeUrl: '{{ route('widgets.store') }}', 
        deleteUrl: '{{ route('widgets.destroy', ['widget' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#widgetsTable', // Sélecteur du tableau HTML
        formSelector: '#widgetForm',   // Sélecteur du formulaire
        modalSelector: '#widgetModal'  // Sélecteur du modal
    });
    widgetCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgWidgets::widget._index')
@endsection
