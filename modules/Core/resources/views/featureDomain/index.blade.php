{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::featureDomain'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'featureDomain',
        crudSelector: '#featureDomain_crud',
        indexUrl: '{{ route('featureDomains.index') }}', 
        createUrl: '{{ route('featureDomains.create') }}',
        editUrl: '{{ route('featureDomains.edit', ['featureDomain' => ':id']) }}',
        showUrl: '{{ route('featureDomains.show', ['featureDomain' => ':id']) }}',
        storeUrl: '{{ route('featureDomains.store') }}', 
        deleteUrl: '{{ route('featureDomains.destroy', ['featureDomain' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('Core::featureDomain._index')
@endsection
