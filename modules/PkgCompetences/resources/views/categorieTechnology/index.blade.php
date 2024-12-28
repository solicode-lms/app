{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::categorieTechnology'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const categorieTechnologyCrud = new GappCrud({
        entity_name: 'categorieTechnology',
        indexUrl: '{{ route('categorieTechnologies.index') }}', 
        createUrl: '{{ route('categorieTechnologies.create') }}',
        editUrl: '{{ route('categorieTechnologies.edit', ['categorieTechnology' => ':id']) }}',
        showUrl: '{{ route('categorieTechnologies.show', ['categorieTechnology' => ':id']) }}',
        storeUrl: '{{ route('categorieTechnologies.store') }}', 
        deleteUrl: '{{ route('categorieTechnologies.destroy', ['categorieTechnology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#categorieTechnologiesTable', // Sélecteur du tableau HTML
        formSelector: '#categorieTechnologyForm',   // Sélecteur du formulaire
        modalSelector: '#categorieTechnologyModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categorieTechnology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categorieTechnology.singular") }}',

    });
    categorieTechnologyCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::categorieTechnology._index')
@endsection
