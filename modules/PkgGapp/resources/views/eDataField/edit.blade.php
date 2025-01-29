{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('PkgGapp::eDataField.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'eDataField',
        filterFormSelector: '#eDataField-crud-filter-form',
        crudSelector: '#card-tab-eDataField', 
        formSelector: '#eDataFieldForm',
        editUrl: '{{ route('eDataFields.edit',  ['eDataField' => ':id']) }}',
        indexUrl: '{{ route('eDataFields.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eDataField.singular") }}',
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
                <div id="card-tab-eDataField" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eDataField-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eDataField-hasmany-tabs-home-tab" data-toggle="pill" href="#eDataField-hasmany-tabs-home" role="tab" aria-controls="eDataField-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eDataField.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="eDataField-hasmany-tabs-eMetadatum-tab" data-toggle="pill" href="#eDataField-hasmany-tabs-eMetadatum" role="tab" aria-controls="eDataField-hasmany-tabs-eMetadatum" aria-selected="false">{{__('PkgGapp::eMetadatum.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eDataField-tabContent">
                            <div class="tab-pane fade show active" id="eDataField-hasmany-tabs-home" role="tabpanel" aria-labelledby="eDataField-hasmany-tabs-home-tab">
                                @include('PkgGapp::eDataField._fields')
                            </div>

                            <div class="tab-pane fade" id="eDataField-hasmany-tabs-eMetadatum" role="tabpanel" aria-labelledby="eDataField-hasmany-tabs-eMetadatum-tab">
                                @include('PkgGapp::eMetadatum._index',['edit_has_many' => true]))
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
