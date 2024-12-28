{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysColor'))
@section('script')
<script>

    window.entitiesConfig = window.entitiesConfig || [];

    window.entitiesConfig.push({
        entity_name: 'sysColor',
        crudSelector: '#sysColor_crud',
        indexUrl: '{{ route('sysColors.index') }}', 
        createUrl: '{{ route('sysColors.create') }}',
        editUrl: '{{ route('sysColors.edit', ['sysColor' => ':id']) }}',
        showUrl: '{{ route('sysColors.show', ['sysColor' => ':id']) }}',
        storeUrl: '{{ route('sysColors.store') }}', 
        deleteUrl: '{{ route('sysColors.destroy', ['sysColor' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        formSelector: '#sysColorForm',   // Sélecteur du formulaire
        modalSelector: '#sysColorModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
});

</script>
@endsection
@section('content')
    @include('Core::sysColor._index')
@endsection
