{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgCreationProjet::natureLivrable'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'natureLivrable',
        crudSelector: '#natureLivrable_crud',
        indexUrl: '{{ route('natureLivrables.index') }}', 
        createUrl: '{{ route('natureLivrables.create') }}',
        editUrl: '{{ route('natureLivrables.edit', ['natureLivrable' => ':id']) }}',
        showUrl: '{{ route('natureLivrables.show', ['natureLivrable' => ':id']) }}',
        storeUrl: '{{ route('natureLivrables.store') }}', 
        deleteUrl: '{{ route('natureLivrables.destroy', ['natureLivrable' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgCreationProjet::natureLivrable._index')
@endsection
