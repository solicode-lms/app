{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::sysModel.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'sysModel',
        filterFormSelector: '#sysModel-crud-filter-form',
        crudSelector: '#card-tab-sysModel', 
        formSelector: '#sysModelForm',
        editUrl: '{{ route('sysModels.edit',  ['sysModel' => ':id']) }}',
        indexUrl: '{{ route('sysModels.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModel.singular") }}',
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
                <div id="card-tab-sysModel" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysModel-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysModel-hasmany-tabs-home-tab" data-toggle="pill" href="#sysModel-hasmany-tabs-home" role="tab" aria-controls="sysModel-hasmany-tabs-home" aria-selected="true">{{__('Core::sysModel.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sysModel-hasmany-tabs-widget-tab" data-toggle="pill" href="#sysModel-hasmany-tabs-widget" role="tab" aria-controls="sysModel-hasmany-tabs-widget" aria-selected="false">{{__('PkgWidgets::widget.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysModel-tabContent">
                            <div class="tab-pane fade show active" id="sysModel-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysModel-hasmany-tabs-home-tab">
                                @include('Core::sysModel._fields')
                            </div>

                            <div class="tab-pane fade" id="sysModel-hasmany-tabs-widget" role="tabpanel" aria-labelledby="sysModel-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false])
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
