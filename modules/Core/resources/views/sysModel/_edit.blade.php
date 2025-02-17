{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sysModel',
        contextKey: 'sysModel.edit_{{ $itemSysModel->id}}',
        cardTabSelector: '#card-tab-sysModel', 
        formSelector: '#sysModelForm',
        editUrl: '{{ route('sysModels.edit',  ['sysModel' => ':id']) }}',
        indexUrl: '{{ route('sysModels.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysModel.singular") }}',
    });
</script>
<script>
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-sysModel" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysModel-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-cubes"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysModel-hasmany-tabs-home-tab" data-toggle="pill" href="#sysModel-hasmany-tabs-home" role="tab" aria-controls="sysModel-hasmany-tabs-home" aria-selected="true">{{__('Core::sysModel.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sysModel-hasmany-tabs-widget-tab" data-toggle="pill" href="#sysModel-hasmany-tabs-widget" role="tab" aria-controls="sysModel-hasmany-tabs-widget" aria-selected="false">{{__('PkgWidgets::widget.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysModel-tabContent">
                            <div class="tab-pane fade show active" id="sysModel-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysModel-hasmany-tabs-home-tab">
                                @include('Core::sysModel._fields')
                            </div>

                            <div class="tab-pane fade" id="sysModel-hasmany-tabs-widget" role="tabpanel" aria-labelledby="sysModel-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysModel.edit_' . $itemSysModel->id])
                            </div>

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@show
