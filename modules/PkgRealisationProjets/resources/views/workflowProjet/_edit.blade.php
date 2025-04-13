{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'workflowProjet',
        contextKey: 'workflowProjet.edit_{{ $itemWorkflowProjet->id}}',
        cardTabSelector: '#card-tab-workflowProjet', 
        formSelector: '#workflowProjetForm',
        editUrl: '{{ route('workflowProjets.edit',  ['workflowProjet' => ':id']) }}',
        indexUrl: '{{ route('workflowProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::workflowProjet.singular") }}',
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
                <div id="card-tab-workflowProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-workflowProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="workflowProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#workflowProjet-hasmany-tabs-home" role="tab" aria-controls="workflowProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationProjets::workflowProjet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="workflowProjet-hasmany-tabs-etatsRealisationProjet-tab" data-toggle="pill" href="#workflowProjet-hasmany-tabs-etatsRealisationProjet" role="tab" aria-controls="workflowProjet-hasmany-tabs-etatsRealisationProjet" aria-selected="false">{{__('PkgRealisationProjets::etatsRealisationProjet.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-workflowProjet-tabContent">
                            <div class="tab-pane fade show active" id="workflowProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="workflowProjet-hasmany-tabs-home-tab">
                                @include('PkgRealisationProjets::workflowProjet._fields')
                            </div>

                            <div class="tab-pane fade" id="workflowProjet-hasmany-tabs-etatsRealisationProjet" role="tabpanel" aria-labelledby="workflowProjet-hasmany-tabs-etatsRealisationProjet-tab">
                                @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowProjet.edit_' . $itemWorkflowProjet->id])
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
