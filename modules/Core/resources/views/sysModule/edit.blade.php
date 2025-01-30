{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.edit') . ' ' . __('Core::sysModule.singular'))

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        page: "edit",
        entity_name: 'sysModule',
        filterFormSelector: '#sysModule-crud-filter-form',
        crudSelector: '#card-tab-sysModule', 
        formSelector: '#sysModuleForm',
        editUrl: '{{ route('sysModules.edit',  ['sysModule' => ':id']) }}',
        indexUrl: '{{ route('sysModules.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
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
                <div id="card-tab-sysModule" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysModule-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysModule-hasmany-tabs-home-tab" data-toggle="pill" href="#sysModule-hasmany-tabs-home" role="tab" aria-controls="sysModule-hasmany-tabs-home" aria-selected="true">{{__('Core::sysModule.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sysModule-hasmany-tabs-featureDomain-tab" data-toggle="pill" href="#sysModule-hasmany-tabs-featureDomain" role="tab" aria-controls="sysModule-hasmany-tabs-featureDomain" aria-selected="false">{{__('Core::featureDomain.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysModule-hasmany-tabs-sysController-tab" data-toggle="pill" href="#sysModule-hasmany-tabs-sysController" role="tab" aria-controls="sysModule-hasmany-tabs-sysController" aria-selected="false">{{__('Core::sysController.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysModule-hasmany-tabs-sysModel-tab" data-toggle="pill" href="#sysModule-hasmany-tabs-sysModel" role="tab" aria-controls="sysModule-hasmany-tabs-sysModel" aria-selected="false">{{__('Core::sysModel.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysModule-tabContent">
                            <div class="tab-pane fade show active" id="sysModule-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysModule-hasmany-tabs-home-tab">
                                @include('Core::sysModule._fields')
                            </div>

                            <div class="tab-pane fade" id="sysModule-hasmany-tabs-featureDomain" role="tabpanel" aria-labelledby="sysModule-hasmany-tabs-featureDomain-tab">
                                @include('Core::featureDomain._index',['isMany' => true])
                            </div>
                            <div class="tab-pane fade" id="sysModule-hasmany-tabs-sysController" role="tabpanel" aria-labelledby="sysModule-hasmany-tabs-sysController-tab">
                                @include('Core::sysController._index',['isMany' => true])
                            </div>
                            <div class="tab-pane fade" id="sysModule-hasmany-tabs-sysModel" role="tabpanel" aria-labelledby="sysModule-hasmany-tabs-sysModel-tab">
                                @include('Core::sysModel._index',['isMany' => true])
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
