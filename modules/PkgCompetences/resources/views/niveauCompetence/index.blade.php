{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::niveauCompetence'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'niveauCompetence',
        crudSelector: '#niveauCompetence_crud',
        indexUrl: '{{ route('niveauCompetences.index') }}', 
        createUrl: '{{ route('niveauCompetences.create') }}',
        editUrl: '{{ route('niveauCompetences.edit', ['niveauCompetence' => ':id']) }}',
        showUrl: '{{ route('niveauCompetences.show', ['niveauCompetence' => ':id']) }}',
        storeUrl: '{{ route('niveauCompetences.store') }}', 
        deleteUrl: '{{ route('niveauCompetences.destroy', ['niveauCompetence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::niveauCompetence._index')
@endsection
