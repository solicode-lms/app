{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sysController',
        contextKey: 'sysController.edit_{{$itemProjet->id}}',
        cardTabSelector: '#card-tab-sysController', 
        formSelector: '#sysControllerForm',
        editUrl: '{{ route('sysControllers.edit',  ['sysController' => ':id']) }}',
        indexUrl: '{{ route('sysControllers.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysController.singular") }}',
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
                <div id="card-tab-sysController" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysController-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysController-hasmany-tabs-home-tab" data-toggle="pill" href="#sysController-hasmany-tabs-home" role="tab" aria-controls="sysController-hasmany-tabs-home" aria-selected="true">{{__('Core::sysController.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sysController-hasmany-tabs-permission-tab" data-toggle="pill" href="#sysController-hasmany-tabs-permission" role="tab" aria-controls="sysController-hasmany-tabs-permission" aria-selected="false">{{__('PkgAutorisation::permission.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysController-tabContent">
                            <div class="tab-pane fade show active" id="sysController-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysController-hasmany-tabs-home-tab">
                                @include('Core::sysController._fields')
                            </div>

                            <div class="tab-pane fade" id="sysController-hasmany-tabs-permission" role="tabpanel" aria-labelledby="sysController-hasmany-tabs-permission-tab">
                                @include('PkgAutorisation::permission._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysController.edit_' . $itemSysController->id])
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
