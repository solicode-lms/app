{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::feature'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const featureCrud = new GappCrud({
        entity_name: 'feature',
        indexUrl: '{{ route('features.index') }}', 
        createUrl: '{{ route('features.create') }}',
        editUrl: '{{ route('features.edit', ['feature' => ':id']) }}',
        showUrl: '{{ route('features.show', ['feature' => ':id']) }}',
        storeUrl: '{{ route('features.store') }}', 
        deleteUrl: '{{ route('features.destroy', ['feature' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#featuresTable', // Sélecteur du tableau HTML
        formSelector: '#featureForm',   // Sélecteur du formulaire
        modalSelector: '#featureModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::feature.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::feature.singular") }}',

    });
    featureCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('Core::feature._index')
@endsection
