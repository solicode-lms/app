{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgWidgets::widgetType'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'widgetType',
        crudSelector: '#widgetType_crud',
        indexUrl: '{{ route('widgetTypes.index') }}', 
        createUrl: '{{ route('widgetTypes.create') }}',
        editUrl: '{{ route('widgetTypes.edit', ['widgetType' => ':id']) }}',
        showUrl: '{{ route('widgetTypes.show', ['widgetType' => ':id']) }}',
        storeUrl: '{{ route('widgetTypes.store') }}', 
        deleteUrl: '{{ route('widgetTypes.destroy', ['widgetType' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widgetType.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgWidgets::widgetType._index')
@endsection
