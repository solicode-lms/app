{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::role'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'role',
        crudSelector: '#role_crud',
        indexUrl: '{{ route('roles.index') }}', 
        createUrl: '{{ route('roles.create') }}',
        editUrl: '{{ route('roles.edit', ['role' => ':id']) }}',
        showUrl: '{{ route('roles.show', ['role' => ':id']) }}',
        storeUrl: '{{ route('roles.store') }}', 
        deleteUrl: '{{ route('roles.destroy', ['role' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::role.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::role.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgAutorisation::role._index')
@endsection
