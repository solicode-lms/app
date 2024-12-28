{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::specialite'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'specialite',
        crudSelector: '#specialite_crud',
        indexUrl: '{{ route('specialites.index') }}', 
        createUrl: '{{ route('specialites.create') }}',
        editUrl: '{{ route('specialites.edit', ['specialite' => ':id']) }}',
        showUrl: '{{ route('specialites.show', ['specialite' => ':id']) }}',
        storeUrl: '{{ route('specialites.store') }}', 
        deleteUrl: '{{ route('specialites.destroy', ['specialite' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::specialite.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::specialite.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::specialite._index')
@endsection
