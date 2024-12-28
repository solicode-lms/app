{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::technology'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'technology',
        crudSelector: '#technology_crud',
        indexUrl: '{{ route('technologies.index') }}', 
        createUrl: '{{ route('technologies.create') }}',
        editUrl: '{{ route('technologies.edit', ['technology' => ':id']) }}',
        showUrl: '{{ route('technologies.show', ['technology' => ':id']) }}',
        storeUrl: '{{ route('technologies.store') }}', 
        deleteUrl: '{{ route('technologies.destroy', ['technology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::technology._index')
@endsection
