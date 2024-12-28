{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::natureLivrable'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const natureLivrableCrud = new GappCrud({
        entity_name: 'natureLivrable',
        indexUrl: '{{ route('natureLivrables.index') }}', 
        createUrl: '{{ route('natureLivrables.create') }}',
        editUrl: '{{ route('natureLivrables.edit', ['natureLivrable' => ':id']) }}',
        showUrl: '{{ route('natureLivrables.show', ['natureLivrable' => ':id']) }}',
        storeUrl: '{{ route('natureLivrables.store') }}', 
        deleteUrl: '{{ route('natureLivrables.destroy', ['natureLivrable' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#natureLivrablesTable', // Sélecteur du tableau HTML
        formSelector: '#natureLivrableForm',   // Sélecteur du formulaire
        modalSelector: '#natureLivrableModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',

    });
    natureLivrableCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCreationProjet::natureLivrable._index')
@endsection
