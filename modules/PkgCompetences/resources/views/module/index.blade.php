{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::module'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const moduleCrud = new GappCrud({
        entity_name: 'module',
        indexUrl: '{{ route('modules.index') }}', 
        createUrl: '{{ route('modules.create') }}',
        editUrl: '{{ route('modules.edit', ['module' => ':id']) }}',
        showUrl: '{{ route('modules.show', ['module' => ':id']) }}',
        storeUrl: '{{ route('modules.store') }}', 
        deleteUrl: '{{ route('modules.destroy', ['module' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#modulesTable', // Sélecteur du tableau HTML
        formSelector: '#moduleForm',   // Sélecteur du formulaire
        modalSelector: '#moduleModal'  // Sélecteur du modal
    });
    moduleCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCompetences::module._index')
@endsection
