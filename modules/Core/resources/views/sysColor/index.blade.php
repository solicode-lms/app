{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::sysColor'))
@section('script')
<script>

document.addEventListener("DOMContentLoaded", function () {
    const sysColorCrud = new GappCrud({
        entity_name: 'sysColor',
        indexUrl: '{{ route('sysColors.index') }}', 
        createUrl: '{{ route('sysColors.create') }}',
        editUrl: '{{ route('sysColors.edit', ['sysColor' => ':id']) }}',
        showUrl: '{{ route('sysColors.show', ['sysColor' => ':id']) }}',
        storeUrl: '{{ route('sysColors.store') }}', 
        deleteUrl: '{{ route('sysColors.destroy', ['sysColor' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        tableSelector: '#sysColorsTable', // Sélecteur du tableau HTML
        formSelector: '#sysColorForm',   // Sélecteur du formulaire
        modalSelector: '#sysColorModal',  // Sélecteur du modal
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',

    });
    sysColorCrud.init(); // Initialisation des fonctionnalités CRUD
});
</script>
@endsection
@section('content')
    @include('Core::sysColor._index')
@endsection
