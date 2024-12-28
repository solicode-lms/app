{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('Core::feature'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'feature',
        crudSelector: '#feature_crud',
        indexUrl: '{{ route('features.index') }}', 
        createUrl: '{{ route('features.create') }}',
        editUrl: '{{ route('features.edit', ['feature' => ':id']) }}',
        showUrl: '{{ route('features.show', ['feature' => ':id']) }}',
        storeUrl: '{{ route('features.store') }}', 
        deleteUrl: '{{ route('features.destroy', ['feature' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::feature.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::feature.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('Core::feature._index')
@endsection
