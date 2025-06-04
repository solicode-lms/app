{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'evaluationRealisationProjet',
        contextKey: 'evaluationRealisationProjet.edit_{{ $itemEvaluationRealisationProjet->id}}',
        cardTabSelector: '#card-tab-evaluationRealisationProjet', 
        formSelector: '#evaluationRealisationProjetForm',
        editUrl: '{{ route('evaluationRealisationProjets.edit',  ['evaluationRealisationProjet' => ':id']) }}',
        indexUrl: '{{ route('evaluationRealisationProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgValidationProjets::evaluationRealisationProjet.singular") }} - {{ $itemEvaluationRealisationProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEvaluationRealisationProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-evaluationRealisationProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-evaluationRealisationProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="evaluationRealisationProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#evaluationRealisationProjet-hasmany-tabs-home" role="tab" aria-controls="evaluationRealisationProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgValidationProjets::evaluationRealisationProjet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="evaluationRealisationProjet-hasmany-tabs-evaluationRealisationTache-tab" data-toggle="pill" href="#evaluationRealisationProjet-hasmany-tabs-evaluationRealisationTache" role="tab" aria-controls="evaluationRealisationProjet-hasmany-tabs-evaluationRealisationTache" aria-selected="false">{{ucfirst(__('PkgValidationProjets::evaluationRealisationTache.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-evaluationRealisationProjet-tabContent">
                            <div class="tab-pane fade show active" id="evaluationRealisationProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="evaluationRealisationProjet-hasmany-tabs-home-tab">
                                @include('PkgValidationProjets::evaluationRealisationProjet._fields')
                            </div>

                            <div class="tab-pane fade" id="evaluationRealisationProjet-hasmany-tabs-evaluationRealisationTache" role="tabpanel" aria-labelledby="evaluationRealisationProjet-hasmany-tabs-evaluationRealisationTache-tab">
                                @include('PkgValidationProjets::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluationRealisationProjet.edit_' . $itemEvaluationRealisationProjet->id])
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
