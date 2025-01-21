{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eModel.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'eModel',
        filterFormSelector: '#eModel-crud-filter-form',
        crudSelector: '#card-tab-eModel', 
        formSelector: '#eModelForm',
        editUrl: '{{ route('eModels.edit',  ['eModel' => ':id']) }}',
        indexUrl: '{{ route('eModels.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eModel.singular") }}',
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
                <div id="card-tab-eModel" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eModel-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eModel-hasmany-tabs-home-tab" data-toggle="pill" href="#eModel-hasmany-tabs-home" role="tab" aria-controls="eModel-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eModel.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="eModel-hasmany-tabs-eDataField-tab" data-toggle="pill" href="#eModel-hasmany-tabs-eDataField" role="tab" aria-controls="eModel-hasmany-tabs-eDataField" aria-selected="false">{{__('PkgGapp::eDataField.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="eModel-hasmany-tabs-eRelationship-tab" data-toggle="pill" href="#eModel-hasmany-tabs-eRelationship" role="tab" aria-controls="eModel-hasmany-tabs-eRelationship" aria-selected="false">{{__('PkgGapp::eRelationship.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="eModel-hasmany-tabs-eRelationship-tab" data-toggle="pill" href="#eModel-hasmany-tabs-eRelationship" role="tab" aria-controls="eModel-hasmany-tabs-eRelationship" aria-selected="false">{{__('PkgGapp::eRelationship.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="eModel-hasmany-tabs-eMetadatum-tab" data-toggle="pill" href="#eModel-hasmany-tabs-eMetadatum" role="tab" aria-controls="eModel-hasmany-tabs-eMetadatum" aria-selected="false">{{__('PkgGapp::eMetadatum.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eModel-tabContent">
                            <div class="tab-pane fade show active" id="eModel-hasmany-tabs-home" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-home-tab">
                                @include('PkgGapp::eModel._fields')
                            </div>

                            <div class="tab-pane fade" id="eModel-hasmany-tabs-eDataField" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-eDataField-tab">
                                @include('PkgGapp::eDataField._index')
                            </div>
                            <div class="tab-pane fade" id="eModel-hasmany-tabs-eRelationship" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-eRelationship-tab">
                                @include('PkgGapp::eRelationship._index')
                            </div>
                            <div class="tab-pane fade" id="eModel-hasmany-tabs-eRelationship" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-eRelationship-tab">
                                @include('PkgGapp::eRelationship._index')
                            </div>
                            <div class="tab-pane fade" id="eModel-hasmany-tabs-eMetadatum" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-eMetadatum-tab">
                                @include('PkgGapp::eMetadatum._index')
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
