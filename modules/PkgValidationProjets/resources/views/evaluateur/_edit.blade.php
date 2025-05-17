{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'evaluateur',
        contextKey: 'evaluateur.edit_{{ $itemEvaluateur->id}}',
        cardTabSelector: '#card-tab-evaluateur', 
        formSelector: '#evaluateurForm',
        editUrl: '{{ route('evaluateurs.edit',  ['evaluateur' => ':id']) }}',
        indexUrl: '{{ route('evaluateurs.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgValidationProjets::evaluateur.singular") }} - {{ $itemEvaluateur }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEvaluateur }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-evaluateur" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-evaluateur-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="evaluateur-hasmany-tabs-home-tab" data-toggle="pill" href="#evaluateur-hasmany-tabs-home" role="tab" aria-controls="evaluateur-hasmany-tabs-home" aria-selected="true">{{__('PkgValidationProjets::evaluateur.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="evaluateur-hasmany-tabs-evaluationRealisationTache-tab" data-toggle="pill" href="#evaluateur-hasmany-tabs-evaluationRealisationTache" role="tab" aria-controls="evaluateur-hasmany-tabs-evaluationRealisationTache" aria-selected="false">{{ucfirst(__('PkgValidationProjets::evaluationRealisationTache.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-evaluateur-tabContent">
                            <div class="tab-pane fade show active" id="evaluateur-hasmany-tabs-home" role="tabpanel" aria-labelledby="evaluateur-hasmany-tabs-home-tab">
                                @include('PkgValidationProjets::evaluateur._fields')
                            </div>

                            <div class="tab-pane fade" id="evaluateur-hasmany-tabs-evaluationRealisationTache" role="tabpanel" aria-labelledby="evaluateur-hasmany-tabs-evaluationRealisationTache-tab">
                                @include('PkgValidationProjets::evaluationRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluateur.edit_' . $itemEvaluateur->id])
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
