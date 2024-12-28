{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::resource'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'resource',
        crudSelector: '#resource_crud',
        indexUrl: '{{ route('resources.index') }}', 
        createUrl: '{{ route('resources.create') }}',
        editUrl: '{{ route('resources.edit', ['resource' => ':id']) }}',
        showUrl: '{{ route('resources.show', ['resource' => ':id']) }}',
        storeUrl: '{{ route('resources.store') }}', 
        deleteUrl: '{{ route('resources.destroy', ['resource' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCreationProjet::resource._index')
@endsection
