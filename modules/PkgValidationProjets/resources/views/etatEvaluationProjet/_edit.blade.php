{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatEvaluationProjet',
        contextKey: 'etatEvaluationProjet.edit_{{ $itemEtatEvaluationProjet->id}}',
        cardTabSelector: '#card-tab-etatEvaluationProjet', 
        formSelector: '#etatEvaluationProjetForm',
        editUrl: '{{ route('etatEvaluationProjets.edit',  ['etatEvaluationProjet' => ':id']) }}',
        indexUrl: '{{ route('etatEvaluationProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgValidationProjets::etatEvaluationProjet.singular") }} - {{ $itemEtatEvaluationProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatEvaluationProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatEvaluationProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatEvaluationProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatEvaluationProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#etatEvaluationProjet-hasmany-tabs-home" role="tab" aria-controls="etatEvaluationProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgValidationProjets::etatEvaluationProjet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatEvaluationProjet-hasmany-tabs-evaluationRealisationProjet-tab" data-toggle="pill" href="#etatEvaluationProjet-hasmany-tabs-evaluationRealisationProjet" role="tab" aria-controls="etatEvaluationProjet-hasmany-tabs-evaluationRealisationProjet" aria-selected="false">{{ucfirst(__('PkgValidationProjets::evaluationRealisationProjet.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatEvaluationProjet-tabContent">
                            <div class="tab-pane fade show active" id="etatEvaluationProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatEvaluationProjet-hasmany-tabs-home-tab">
                                @include('PkgValidationProjets::etatEvaluationProjet._fields')
                            </div>

                            <div class="tab-pane fade" id="etatEvaluationProjet-hasmany-tabs-evaluationRealisationProjet" role="tabpanel" aria-labelledby="etatEvaluationProjet-hasmany-tabs-evaluationRealisationProjet-tab">
                                @include('PkgValidationProjets::evaluationRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatEvaluationProjet.edit_' . $itemEtatEvaluationProjet->id])
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
