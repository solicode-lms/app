{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::apprenant'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'apprenant',
        crudSelector: '#apprenant_crud',
        indexUrl: '{{ route('apprenants.index') }}', 
        createUrl: '{{ route('apprenants.create') }}',
        editUrl: '{{ route('apprenants.edit', ['apprenant' => ':id']) }}',
        showUrl: '{{ route('apprenants.show', ['apprenant' => ':id']) }}',
        storeUrl: '{{ route('apprenants.store') }}', 
        deleteUrl: '{{ route('apprenants.destroy', ['apprenant' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::apprenant.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::apprenant.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::apprenant._index')
@endsection
