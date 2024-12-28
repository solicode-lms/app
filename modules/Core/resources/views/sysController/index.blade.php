{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysController'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'sysController',
        crudSelector: '#sysController_crud',
        indexUrl: '{{ route('sysControllers.index') }}', 
        createUrl: '{{ route('sysControllers.create') }}',
        editUrl: '{{ route('sysControllers.edit', ['sysController' => ':id']) }}',
        showUrl: '{{ route('sysControllers.show', ['sysController' => ':id']) }}',
        storeUrl: '{{ route('sysControllers.store') }}', 
        deleteUrl: '{{ route('sysControllers.destroy', ['sysController' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysController.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('Core::sysController._index')
@endsection
