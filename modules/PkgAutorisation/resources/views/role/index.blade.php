{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::role'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const roleCrud = new GappCrud({
        entity_name: 'role',
        indexUrl: '{{ route('roles.index') }}', 
        createUrl: '{{ route('roles.create') }}',
        editUrl: '{{ route('roles.edit', ['role' => ':id']) }}',
        showUrl: '{{ route('roles.show', ['role' => ':id']) }}',
        storeUrl: '{{ route('roles.store') }}', 
        deleteUrl: '{{ route('roles.destroy', ['role' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#rolesTable', // Sélecteur du tableau HTML
        formSelector: '#roleForm',   // Sélecteur du formulaire
        modalSelector: '#roleModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::role.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::role.singular") }}',

    });
    roleCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgAutorisation::role._index')
@endsection
