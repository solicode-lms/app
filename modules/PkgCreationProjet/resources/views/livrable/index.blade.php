{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::livrable'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const livrableCrud = new GappCrud({
        entity_name: 'livrable',
        indexUrl: '{{ route('livrables.index') }}', 
        createUrl: '{{ route('livrables.create') }}',
        editUrl: '{{ route('livrables.edit', ['livrable' => ':id']) }}',
        showUrl: '{{ route('livrables.show', ['livrable' => ':id']) }}',
        storeUrl: '{{ route('livrables.store') }}', 
        deleteUrl: '{{ route('livrables.destroy', ['livrable' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#livrablesTable', // Sélecteur du tableau HTML
        formSelector: '#livrableForm',   // Sélecteur du formulaire
        modalSelector: '#livrableModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',

    });
    livrableCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgCreationProjet::livrable._index')
@endsection
