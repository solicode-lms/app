{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::livrable'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'livrable',
        crudSelector: '#livrable_crud',
        indexUrl: '{{ route('livrables.index') }}', 
        createUrl: '{{ route('livrables.create') }}',
        editUrl: '{{ route('livrables.edit', ['livrable' => ':id']) }}',
        showUrl: '{{ route('livrables.show', ['livrable' => ':id']) }}',
        storeUrl: '{{ route('livrables.store') }}', 
        deleteUrl: '{{ route('livrables.destroy', ['livrable' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCreationProjet::livrable._index')
@endsection
