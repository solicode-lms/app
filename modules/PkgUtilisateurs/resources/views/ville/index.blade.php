{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::ville'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'ville',
        crudSelector: '#ville_crud',
        indexUrl: '{{ route('villes.index') }}', 
        createUrl: '{{ route('villes.create') }}',
        editUrl: '{{ route('villes.edit', ['ville' => ':id']) }}',
        showUrl: '{{ route('villes.show', ['ville' => ':id']) }}',
        storeUrl: '{{ route('villes.store') }}', 
        deleteUrl: '{{ route('villes.destroy', ['ville' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::ville._index')
@endsection
