{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::ville'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const villeCrud = new GappCrud({
        entity_name: 'ville',
        indexUrl: '{{ route('villes.index') }}', 
        createUrl: '{{ route('villes.create') }}',
        editUrl: '{{ route('villes.edit', ['ville' => ':id']) }}',
        showUrl: '{{ route('villes.show', ['ville' => ':id']) }}',
        storeUrl: '{{ route('villes.store') }}', 
        deleteUrl: '{{ route('villes.destroy', ['ville' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#villesTable', // Sélecteur du tableau HTML
        formSelector: '#villeForm',   // Sélecteur du formulaire
        modalSelector: '#villeModal'  // Sélecteur du modal
    });
    villeCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::ville._index')
@endsection
