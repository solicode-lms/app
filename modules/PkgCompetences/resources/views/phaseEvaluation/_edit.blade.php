{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'phaseEvaluation',
        contextKey: 'phaseEvaluation.edit_{{ $itemPhaseEvaluation->id}}',
        cardTabSelector: '#card-tab-phaseEvaluation', 
        formSelector: '#phaseEvaluationForm',
        editUrl: '{{ route('phaseEvaluations.edit',  ['phaseEvaluation' => ':id']) }}',
        indexUrl: '{{ route('phaseEvaluations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::phaseEvaluation.singular") }} - {{ $itemPhaseEvaluation }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemPhaseEvaluation }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-phaseEvaluation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-phaseEvaluation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-battery-three-quarters"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="phaseEvaluation-hasmany-tabs-home-tab" data-toggle="pill" href="#phaseEvaluation-hasmany-tabs-home" role="tab" aria-controls="phaseEvaluation-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::phaseEvaluation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="phaseEvaluation-hasmany-tabs-critereEvaluation-tab" data-toggle="pill" href="#phaseEvaluation-hasmany-tabs-critereEvaluation" role="tab" aria-controls="phaseEvaluation-hasmany-tabs-critereEvaluation" aria-selected="false">{{ucfirst(__('PkgCompetences::critereEvaluation.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-phaseEvaluation-tabContent">
                            <div class="tab-pane fade show active" id="phaseEvaluation-hasmany-tabs-home" role="tabpanel" aria-labelledby="phaseEvaluation-hasmany-tabs-home-tab">
                                @include('PkgCompetences::phaseEvaluation._fields')
                            </div>

                            <div class="tab-pane fade" id="phaseEvaluation-hasmany-tabs-critereEvaluation" role="tabpanel" aria-labelledby="phaseEvaluation-hasmany-tabs-critereEvaluation-tab">
                                @include('PkgCompetences::critereEvaluation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'phaseEvaluation.edit_' . $itemPhaseEvaluation->id])
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
