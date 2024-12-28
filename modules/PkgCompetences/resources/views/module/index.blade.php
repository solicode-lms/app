{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCompetences::module'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'module',
        crudSelector: '#module_crud',
        indexUrl: '{{ route('modules.index') }}', 
        createUrl: '{{ route('modules.create') }}',
        editUrl: '{{ route('modules.edit', ['module' => ':id']) }}',
        showUrl: '{{ route('modules.show', ['module' => ':id']) }}',
        storeUrl: '{{ route('modules.store') }}', 
        deleteUrl: '{{ route('modules.destroy', ['module' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::module.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::module.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCompetences::module._index')
@endsection
