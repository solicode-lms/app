{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::groupe'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'groupe',
        crudSelector: '#groupe_crud',
        indexUrl: '{{ route('groupes.index') }}', 
        createUrl: '{{ route('groupes.create') }}',
        editUrl: '{{ route('groupes.edit', ['groupe' => ':id']) }}',
        showUrl: '{{ route('groupes.show', ['groupe' => ':id']) }}',
        storeUrl: '{{ route('groupes.store') }}', 
        deleteUrl: '{{ route('groupes.destroy', ['groupe' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::groupe.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::groupe.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::groupe._index')
@endsection
