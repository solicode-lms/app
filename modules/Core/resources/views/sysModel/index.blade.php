{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysModel'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'sysModel',
        crudSelector: '#sysModel_crud',
        indexUrl: '{{ route('sysModels.index') }}', 
        createUrl: '{{ route('sysModels.create') }}',
        editUrl: '{{ route('sysModels.edit', ['sysModel' => ':id']) }}',
        showUrl: '{{ route('sysModels.show', ['sysModel' => ':id']) }}',
        storeUrl: '{{ route('sysModels.store') }}', 
        deleteUrl: '{{ route('sysModels.destroy', ['sysModel' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('Core::sysModel._index')
@endsection
