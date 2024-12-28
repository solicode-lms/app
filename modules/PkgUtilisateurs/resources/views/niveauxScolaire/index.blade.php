{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::niveauxScolaire'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const niveauxScolaireCrud = new GappCrud({
        entity_name: 'niveauxScolaire',
        indexUrl: '{{ route('niveauxScolaires.index') }}', 
        createUrl: '{{ route('niveauxScolaires.create') }}',
        editUrl: '{{ route('niveauxScolaires.edit', ['niveauxScolaire' => ':id']) }}',
        showUrl: '{{ route('niveauxScolaires.show', ['niveauxScolaire' => ':id']) }}',
        storeUrl: '{{ route('niveauxScolaires.store') }}', 
        deleteUrl: '{{ route('niveauxScolaires.destroy', ['niveauxScolaire' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#niveauxScolairesTable', // Sélecteur du tableau HTML
        formSelector: '#niveauxScolaireForm',   // Sélecteur du formulaire
        modalSelector: '#niveauxScolaireModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::niveauxScolaire.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::niveauxScolaire.singular") }}',

    });
    niveauxScolaireCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::niveauxScolaire._index')
@endsection
