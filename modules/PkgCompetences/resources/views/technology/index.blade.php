{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::technology'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const technologyCrud = new GappCrud({
        entity_name: 'technology',
        indexUrl: '{{ route('technologies.index') }}', 
        createUrl: '{{ route('technologies.create') }}',
        editUrl: '{{ route('technologies.edit', ['technology' => ':id']) }}',
        showUrl: '{{ route('technologies.show', ['technology' => ':id']) }}',
        storeUrl: '{{ route('technologies.store') }}', 
        deleteUrl: '{{ route('technologies.destroy', ['technology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#technologiesTable', // Sélecteur du tableau HTML
        formSelector: '#technologyForm',   // Sélecteur du formulaire
        modalSelector: '#technologyModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',

    });
    technologyCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::technology._index')
@endsection
