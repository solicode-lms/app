{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::categorieTechnology'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'categorieTechnology',
        crudSelector: '#categorieTechnology_crud',
        indexUrl: '{{ route('categorieTechnologies.index') }}', 
        createUrl: '{{ route('categorieTechnologies.create') }}',
        editUrl: '{{ route('categorieTechnologies.edit', ['categorieTechnology' => ':id']) }}',
        showUrl: '{{ route('categorieTechnologies.show', ['categorieTechnology' => ':id']) }}',
        storeUrl: '{{ route('categorieTechnologies.store') }}', 
        deleteUrl: '{{ route('categorieTechnologies.destroy', ['categorieTechnology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categorieTechnology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categorieTechnology.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::categorieTechnology._index')
@endsection
