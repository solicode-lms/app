{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'uniteApprentissage',
        contextKey: 'uniteApprentissage.edit_{{ $itemUniteApprentissage->id}}',
        cardTabSelector: '#card-tab-uniteApprentissage', 
        formSelector: '#uniteApprentissageForm',
        editUrl: '{{ route('uniteApprentissages.edit',  ['uniteApprentissage' => ':id']) }}',
        indexUrl: '{{ route('uniteApprentissages.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::uniteApprentissage.singular") }} - {{ $itemUniteApprentissage }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemUniteApprentissage }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-uniteApprentissage" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-uniteApprentissage-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-puzzle-piece"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="uniteApprentissage-hasmany-tabs-home-tab" data-toggle="pill" href="#uniteApprentissage-hasmany-tabs-home" role="tab" aria-controls="uniteApprentissage-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::uniteApprentissage.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="uniteApprentissage-hasmany-tabs-chapitre-tab" data-toggle="pill" href="#uniteApprentissage-hasmany-tabs-chapitre" role="tab" aria-controls="uniteApprentissage-hasmany-tabs-chapitre" aria-selected="false">{{ucfirst(__('PkgCompetences::chapitre.plural'))}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="uniteApprentissage-hasmany-tabs-critereEvaluation-tab" data-toggle="pill" href="#uniteApprentissage-hasmany-tabs-critereEvaluation" role="tab" aria-controls="uniteApprentissage-hasmany-tabs-critereEvaluation" aria-selected="false">{{ucfirst(__('PkgCompetences::critereEvaluation.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-uniteApprentissage-tabContent">
                            <div class="tab-pane fade show active" id="uniteApprentissage-hasmany-tabs-home" role="tabpanel" aria-labelledby="uniteApprentissage-hasmany-tabs-home-tab">
                                @include('PkgCompetences::uniteApprentissage._fields')
                            </div>

                            <div class="tab-pane fade" id="uniteApprentissage-hasmany-tabs-chapitre" role="tabpanel" aria-labelledby="uniteApprentissage-hasmany-tabs-chapitre-tab">
                                @include('PkgCompetences::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'uniteApprentissage.edit_' . $itemUniteApprentissage->id])
                            </div>
                            <div class="tab-pane fade" id="uniteApprentissage-hasmany-tabs-critereEvaluation" role="tabpanel" aria-labelledby="uniteApprentissage-hasmany-tabs-critereEvaluation-tab">
                                @include('PkgCompetences::critereEvaluation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'uniteApprentissage.edit_' . $itemUniteApprentissage->id])
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
