{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysModule'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'sysModule',
        crudSelector: '#sysModule_crud',
        indexUrl: '{{ route('sysModules.index') }}', 
        createUrl: '{{ route('sysModules.create') }}',
        editUrl: '{{ route('sysModules.edit', ['sysModule' => ':id']) }}',
        showUrl: '{{ route('sysModules.show', ['sysModule' => ':id']) }}',
        storeUrl: '{{ route('sysModules.store') }}', 
        deleteUrl: '{{ route('sysModules.destroy', ['sysModule' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('Core::sysModule._index')
@endsection
