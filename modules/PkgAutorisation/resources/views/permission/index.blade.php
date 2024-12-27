{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::permission'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const permissionCrud = new GappCrud({
        entity_name: 'permission',
        indexUrl: '{{ route('permissions.index') }}', 
        createUrl: '{{ route('permissions.create') }}',
        editUrl: '{{ route('permissions.edit', ['permission' => ':id']) }}',
        showUrl: '{{ route('permissions.show', ['permission' => ':id']) }}',
        storeUrl: '{{ route('permissions.store') }}', 
        deleteUrl: '{{ route('permissions.destroy', ['permission' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#permissionsTable', // Sélecteur du tableau HTML
        formSelector: '#permissionForm',   // Sélecteur du formulaire
        modalSelector: '#permissionModal'  // Sélecteur du modal
    });
    permissionCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgAutorisation::permission._index')
@endsection
