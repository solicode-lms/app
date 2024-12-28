{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::appreciation'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'appreciation',
        crudSelector: '#appreciation_crud',
        indexUrl: '{{ route('appreciations.index') }}', 
        createUrl: '{{ route('appreciations.create') }}',
        editUrl: '{{ route('appreciations.edit', ['appreciation' => ':id']) }}',
        showUrl: '{{ route('appreciations.show', ['appreciation' => ':id']) }}',
        storeUrl: '{{ route('appreciations.store') }}', 
        deleteUrl: '{{ route('appreciations.destroy', ['appreciation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::appreciation.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::appreciation.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::appreciation._index')
@endsection
