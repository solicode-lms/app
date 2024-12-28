{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::competence'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'competence',
        crudSelector: '#competence_crud',
        indexUrl: '{{ route('competences.index') }}', 
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit', ['competence' => ':id']) }}',
        showUrl: '{{ route('competences.show', ['competence' => ':id']) }}',
        storeUrl: '{{ route('competences.store') }}', 
        deleteUrl: '{{ route('competences.destroy', ['competence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::competence._index')
@endsection
