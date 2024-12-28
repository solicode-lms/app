{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgWidgets::widgetOperation'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'widgetOperation',
        crudSelector: '#widgetOperation_crud',
        indexUrl: '{{ route('widgetOperations.index') }}', 
        createUrl: '{{ route('widgetOperations.create') }}',
        editUrl: '{{ route('widgetOperations.edit', ['widgetOperation' => ':id']) }}',
        showUrl: '{{ route('widgetOperations.show', ['widgetOperation' => ':id']) }}',
        storeUrl: '{{ route('widgetOperations.store') }}', 
        deleteUrl: '{{ route('widgetOperations.destroy', ['widgetOperation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgWidgets::widgetOperation._index')
@endsection
