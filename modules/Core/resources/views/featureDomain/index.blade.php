{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::featureDomain'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const featureDomainCrud = new GappCrud({
        entity_name: 'featureDomain',
        indexUrl: '{{ route('featureDomains.index') }}', 
        createUrl: '{{ route('featureDomains.create') }}',
        editUrl: '{{ route('featureDomains.edit', ['featureDomain' => ':id']) }}',
        showUrl: '{{ route('featureDomains.show', ['featureDomain' => ':id']) }}',
        storeUrl: '{{ route('featureDomains.store') }}', 
        deleteUrl: '{{ route('featureDomains.destroy', ['featureDomain' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#featureDomainsTable', // Sélecteur du tableau HTML
        formSelector: '#featureDomainForm',   // Sélecteur du formulaire
        modalSelector: '#featureDomainModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',

    });
    featureDomainCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('Core::featureDomain._index')
@endsection
