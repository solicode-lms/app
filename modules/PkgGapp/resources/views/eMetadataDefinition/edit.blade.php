{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eMetadataDefinition.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'eMetadataDefinition',
        filterFormSelector: '#eMetadataDefinition-crud-filter-form',
        crudSelector: '#card-tab-eMetadataDefinition', 
        formSelector: '#eMetadataDefinitionForm',
        editUrl: '{{ route('eMetadataDefinitions.edit',  ['eMetadataDefinition' => ':id']) }}',
        indexUrl: '{{ route('eMetadataDefinitions.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadataDefinition.singular") }}',
    });
</script>
@endpush


@section('content')
    <div class="content-header">
    <!-- debug
    @foreach ($contextState as $key => $value)
    Key: {{ $key }}, Value: {{ $value }}<br>
    @endforeach
     -->

    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-eMetadataDefinition" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eMetadataDefinition-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eMetadataDefinition-hasmany-tabs-home-tab" data-toggle="pill" href="#eMetadataDefinition-hasmany-tabs-home" role="tab" aria-controls="eMetadataDefinition-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eMetadataDefinition.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="eMetadataDefinition-hasmany-tabs-eMetadataDefinition-tab" data-toggle="pill" href="#eMetadataDefinition-hasmany-tabs-eMetadataDefinition" role="tab" aria-controls="eMetadataDefinition-hasmany-tabs-eMetadataDefinition" aria-selected="false">{{__('PkgGapp::eMetadataDefinition.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eMetadataDefinition-tabContent">
                            <div class="tab-pane fade show active" id="eMetadataDefinition-hasmany-tabs-home" role="tabpanel" aria-labelledby="eMetadataDefinition-hasmany-tabs-home-tab">
                                @include('PkgGapp::eMetadataDefinition._fields')
                            </div>

                            <div class="tab-pane fade" id="eMetadataDefinition-hasmany-tabs-eMetadataDefinition" role="tabpanel" aria-labelledby="eMetadataDefinition-hasmany-tabs-eMetadataDefinition-tab">
                                @include('PkgGapp::eMetadataDefinition._index')
                            </div>

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@endsection
