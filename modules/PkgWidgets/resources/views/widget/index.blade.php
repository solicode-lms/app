{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', curd_index_title('PkgWidgets::widget'))
@section('script')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'widget',
        crudSelector: '#widget_crud',
        indexUrl: '{{ route('widgets.index') }}', 
        createUrl: '{{ route('widgets.create') }}',
        editUrl: '{{ route('widgets.edit', ['widget' => ':id']) }}',
        showUrl: '{{ route('widgets.show', ['widget' => ':id']) }}',
        storeUrl: '{{ route('widgets.store') }}', 
        deleteUrl: '{{ route('widgets.destroy', ['widget' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widget.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widget.singular") }}',
    });
</script>
@endsection
@section('content')
    @include('PkgWidgets::widget._index')
@endsection
