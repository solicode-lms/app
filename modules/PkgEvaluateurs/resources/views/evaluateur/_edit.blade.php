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
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgEvaluateurs::evaluateur.singular") }} - {{ $itemEvaluateur }}',
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
                                <i class="nav-icon fas fa-user-check"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="evaluateur-hasmany-tabs-home-tab" data-toggle="pill" href="#evaluateur-hasmany-tabs-home" role="tab" aria-controls="evaluateur-hasmany-tabs-home" aria-selected="true">{{__('PkgEvaluateurs::evaluateur.singular')}}</a>
                        </li>

                         @if($itemEvaluateur->evaluationRealisationProjets->count() > 0 || auth()->user()?->can('create-evaluationRealisationProjet'))
                        <li class="nav-item">
                            <a class="nav-link" id="evaluateur-hasmany-tabs-evaluationRealisationProjet-tab" data-toggle="pill" href="#evaluateur-hasmany-tabs-evaluationRealisationProjet" role="tab" aria-controls="evaluateur-hasmany-tabs-evaluationRealisationProjet" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgEvaluateurs::evaluationRealisationProjet.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-evaluateur-tabContent">
                            <div class="tab-pane fade show active" id="evaluateur-hasmany-tabs-home" role="tabpanel" aria-labelledby="evaluateur-hasmany-tabs-home-tab">
                                @include('PkgEvaluateurs::evaluateur._fields')
                            </div>

                            @if($itemEvaluateur->evaluationRealisationProjets->count() > 0 || auth()->user()?->can('create-evaluationRealisationProjet'))
                            <div class="tab-pane fade" id="evaluateur-hasmany-tabs-evaluationRealisationProjet" role="tabpanel" aria-labelledby="evaluateur-hasmany-tabs-evaluationRealisationProjet-tab">
                                @include('PkgEvaluateurs::evaluationRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'evaluateur.edit_' . $itemEvaluateur->id])
                            </div>
                            @endif

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@show
