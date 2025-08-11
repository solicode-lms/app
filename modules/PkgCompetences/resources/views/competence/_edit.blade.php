{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'competence',
        contextKey: 'competence.edit_{{ $itemCompetence->id}}',
        cardTabSelector: '#card-tab-competence', 
        formSelector: '#competenceForm',
        editUrl: '{{ route('competences.edit',  ['competence' => ':id']) }}',
        indexUrl: '{{ route('competences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::competence.singular") }} - {{ $itemCompetence }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemCompetence }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-competence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-competence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-user-graduate"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="competence-hasmany-tabs-home-tab" data-toggle="pill" href="#competence-hasmany-tabs-home" role="tab" aria-controls="competence-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::competence.singular')}}</a>
                        </li>

                         @if($itemCompetence->microCompetences->count() > 0 || auth()->user()?->can('create-microCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="competence-hasmany-tabs-microCompetence-tab" data-toggle="pill" href="#competence-hasmany-tabs-microCompetence" role="tab" aria-controls="competence-hasmany-tabs-microCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-book"></i>
                                {{ucfirst(__('PkgCompetences::microCompetence.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemCompetence->realisationCompetences->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="competence-hasmany-tabs-realisationCompetence-tab" data-toggle="pill" href="#competence-hasmany-tabs-realisationCompetence" role="tab" aria-controls="competence-hasmany-tabs-realisationCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgApprentissage::realisationCompetence.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-competence-tabContent">
                            <div class="tab-pane fade show active" id="competence-hasmany-tabs-home" role="tabpanel" aria-labelledby="competence-hasmany-tabs-home-tab">
                                @include('PkgCompetences::competence._fields')
                            </div>

                            @if($itemCompetence->microCompetences->count() > 0 || auth()->user()?->can('create-microCompetence'))
                            <div class="tab-pane fade" id="competence-hasmany-tabs-microCompetence" role="tabpanel" aria-labelledby="competence-hasmany-tabs-microCompetence-tab">
                                @include('PkgCompetences::microCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'competence.edit_' . $itemCompetence->id])
                            </div>
                            @endif
                            @if($itemCompetence->realisationCompetences->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                            <div class="tab-pane fade" id="competence-hasmany-tabs-realisationCompetence" role="tabpanel" aria-labelledby="competence-hasmany-tabs-realisationCompetence-tab">
                                @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'competence.edit_' . $itemCompetence->id])
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
