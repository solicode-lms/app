{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::user'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const userCrud = new GappCrud({
        entity_name: 'user',
        indexUrl: '{{ route('users.index') }}', 
        createUrl: '{{ route('users.create') }}',
        editUrl: '{{ route('users.edit', ['user' => ':id']) }}',
        showUrl: '{{ route('users.show', ['user' => ':id']) }}',
        storeUrl: '{{ route('users.store') }}', 
        deleteUrl: '{{ route('users.destroy', ['user' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#usersTable', // Sélecteur du tableau HTML
        formSelector: '#userForm',   // Sélecteur du formulaire
        modalSelector: '#userModal'  // Sélecteur du modal
    });
    userCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgAutorisation::user._index')
@endsection
