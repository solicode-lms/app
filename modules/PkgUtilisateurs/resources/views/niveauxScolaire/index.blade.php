{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::niveauxScolaire'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'niveauxScolaire',
        crudSelector: '#niveauxScolaire_crud',
        indexUrl: '{{ route('niveauxScolaires.index') }}', 
        createUrl: '{{ route('niveauxScolaires.create') }}',
        editUrl: '{{ route('niveauxScolaires.edit', ['niveauxScolaire' => ':id']) }}',
        showUrl: '{{ route('niveauxScolaires.show', ['niveauxScolaire' => ':id']) }}',
        storeUrl: '{{ route('niveauxScolaires.store') }}', 
        deleteUrl: '{{ route('niveauxScolaires.destroy', ['niveauxScolaire' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::niveauxScolaire.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::niveauxScolaire.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::niveauxScolaire._index')
@endsection
