{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'workflowFormation',
        contextKey: 'workflowFormation.edit_{{ $itemWorkflowFormation->id}}',
        cardTabSelector: '#card-tab-workflowFormation', 
        formSelector: '#workflowFormationForm',
        editUrl: '{{ route('workflowFormations.edit',  ['workflowFormation' => ':id']) }}',
        indexUrl: '{{ route('workflowFormations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::workflowFormation.singular") }}',
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
                <div id="card-tab-workflowFormation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-workflowFormation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="workflowFormation-hasmany-tabs-home-tab" data-toggle="pill" href="#workflowFormation-hasmany-tabs-home" role="tab" aria-controls="workflowFormation-hasmany-tabs-home" aria-selected="true">{{__('PkgAutoformation::workflowFormation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="workflowFormation-hasmany-tabs-etatFormation-tab" data-toggle="pill" href="#workflowFormation-hasmany-tabs-etatFormation" role="tab" aria-controls="workflowFormation-hasmany-tabs-etatFormation" aria-selected="false">{{__('PkgAutoformation::etatFormation.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-workflowFormation-tabContent">
                            <div class="tab-pane fade show active" id="workflowFormation-hasmany-tabs-home" role="tabpanel" aria-labelledby="workflowFormation-hasmany-tabs-home-tab">
                                @include('PkgAutoformation::workflowFormation._fields')
                            </div>

                            <div class="tab-pane fade" id="workflowFormation-hasmany-tabs-etatFormation" role="tabpanel" aria-labelledby="workflowFormation-hasmany-tabs-etatFormation-tab">
                                @include('PkgAutoformation::etatFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowFormation.edit_' . $itemWorkflowFormation->id])
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
