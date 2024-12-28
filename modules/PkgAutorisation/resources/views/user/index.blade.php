{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgAutorisation::user'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'user',
        crudSelector: '#user_crud',
        indexUrl: '{{ route('users.index') }}', 
        createUrl: '{{ route('users.create') }}',
        editUrl: '{{ route('users.edit', ['user' => ':id']) }}',
        showUrl: '{{ route('users.show', ['user' => ':id']) }}',
        storeUrl: '{{ route('users.store') }}', 
        deleteUrl: '{{ route('users.destroy', ['user' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::user.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgAutorisation::user.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgAutorisation::user._index')
@endsection
