{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::nationalite'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'nationalite',
        crudSelector: '#nationalite_crud',
        indexUrl: '{{ route('nationalites.index') }}', 
        createUrl: '{{ route('nationalites.create') }}',
        editUrl: '{{ route('nationalites.edit', ['nationalite' => ':id']) }}',
        showUrl: '{{ route('nationalites.show', ['nationalite' => ':id']) }}',
        storeUrl: '{{ route('nationalites.store') }}', 
        deleteUrl: '{{ route('nationalites.destroy', ['nationalite' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::nationalite.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::nationalite.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::nationalite._index')
@endsection
