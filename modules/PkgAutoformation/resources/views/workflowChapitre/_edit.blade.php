{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'workflowChapitre',
        contextKey: 'workflowChapitre.edit_{{ $itemWorkflowChapitre->id}}',
        cardTabSelector: '#card-tab-workflowChapitre', 
        formSelector: '#workflowChapitreForm',
        editUrl: '{{ route('workflowChapitres.edit',  ['workflowChapitre' => ':id']) }}',
        indexUrl: '{{ route('workflowChapitres.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::workflowChapitre.singular") }}',
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
                <div id="card-tab-workflowChapitre" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-workflowChapitre-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="workflowChapitre-hasmany-tabs-home-tab" data-toggle="pill" href="#workflowChapitre-hasmany-tabs-home" role="tab" aria-controls="workflowChapitre-hasmany-tabs-home" aria-selected="true">{{__('PkgAutoformation::workflowChapitre.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="workflowChapitre-hasmany-tabs-etatChapitre-tab" data-toggle="pill" href="#workflowChapitre-hasmany-tabs-etatChapitre" role="tab" aria-controls="workflowChapitre-hasmany-tabs-etatChapitre" aria-selected="false">{{__('PkgAutoformation::etatChapitre.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-workflowChapitre-tabContent">
                            <div class="tab-pane fade show active" id="workflowChapitre-hasmany-tabs-home" role="tabpanel" aria-labelledby="workflowChapitre-hasmany-tabs-home-tab">
                                @include('PkgAutoformation::workflowChapitre._fields')
                            </div>

                            <div class="tab-pane fade" id="workflowChapitre-hasmany-tabs-etatChapitre" role="tabpanel" aria-labelledby="workflowChapitre-hasmany-tabs-etatChapitre-tab">
                                @include('PkgAutoformation::etatChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowChapitre.edit_' . $itemWorkflowChapitre->id])
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
