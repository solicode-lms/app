{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgUtilisateurs::formateur'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'formateur',
        crudSelector: '#formateur_crud',
        indexUrl: '{{ route('formateurs.index') }}', 
        createUrl: '{{ route('formateurs.create') }}',
        editUrl: '{{ route('formateurs.edit', ['formateur' => ':id']) }}',
        showUrl: '{{ route('formateurs.show', ['formateur' => ':id']) }}',
        storeUrl: '{{ route('formateurs.store') }}', 
        deleteUrl: '{{ route('formateurs.destroy', ['formateur' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::formateur.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::formateur.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgUtilisateurs::formateur._index')
@endsection
