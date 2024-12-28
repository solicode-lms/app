{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::permission'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'permission',
        crudSelector: '#permission_crud',
        indexUrl: '{{ route('permissions.index') }}', 
        createUrl: '{{ route('permissions.create') }}',
        editUrl: '{{ route('permissions.edit', ['permission' => ':id']) }}',
        showUrl: '{{ route('permissions.show', ['permission' => ':id']) }}',
        storeUrl: '{{ route('permissions.store') }}', 
        deleteUrl: '{{ route('permissions.destroy', ['permission' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::permission.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::permission.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgAutorisation::permission._index')
@endsection
