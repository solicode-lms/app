{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::filiere'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'filiere',
        crudSelector: '#filiere_crud',
        indexUrl: '{{ route('filieres.index') }}', 
        createUrl: '{{ route('filieres.create') }}',
        editUrl: '{{ route('filieres.edit', ['filiere' => ':id']) }}',
        showUrl: '{{ route('filieres.show', ['filiere' => ':id']) }}',
        storeUrl: '{{ route('filieres.store') }}', 
        deleteUrl: '{{ route('filieres.destroy', ['filiere' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::filiere.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::filiere.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::filiere._index')
@endsection
