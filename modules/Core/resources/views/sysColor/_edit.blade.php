{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sysColor',
        contextKey: 'sysColor.edit_{{ $itemSysColor->id}}',
        cardTabSelector: '#card-tab-sysColor', 
        formSelector: '#sysColorForm',
        editUrl: '{{ route('sysColors.edit',  ['sysColor' => ':id']) }}',
        indexUrl: '{{ route('sysColors.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysColor.singular") }} - {{ $itemSysColor }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemSysColor }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-sysColor" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysColor-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-palette"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysColor-hasmany-tabs-home-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-home" role="tab" aria-controls="sysColor-hasmany-tabs-home" aria-selected="true">{{__('Core::sysColor.singular')}}</a>
                        </li>

                         @if($itemSysColor->etatRealisationTaches?->count() > 0 || auth()->user()?->can('create-etatRealisationTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationTache-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationTache" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationTache" aria-selected="false">
                                <i class="nav-icon fas fa-check"></i>
                                {{ucfirst(__('PkgRealisationTache::etatRealisationTache.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->sysModels?->count() > 0 || auth()->user()?->can('create-sysModel'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModel-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModel" role="tab" aria-controls="sysColor-hasmany-tabs-sysModel" aria-selected="false">
                                <i class="nav-icon fas fa-cubes"></i>
                                {{ucfirst(__('Core::sysModel.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatEvaluationProjets?->count() > 0 || auth()->user()?->can('create-etatEvaluationProjet'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatEvaluationProjet-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatEvaluationProjet" role="tab" aria-controls="sysColor-hasmany-tabs-etatEvaluationProjet" aria-selected="false">
                                <i class="nav-icon fa-table"></i>
                                {{ucfirst(__('PkgEvaluateurs::etatEvaluationProjet.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatRealisationChapitres?->count() > 0 || auth()->user()?->can('create-etatRealisationChapitre'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationChapitre-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationChapitre" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationChapitre" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgApprentissage::etatRealisationChapitre.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->sysModules?->count() > 0 || auth()->user()?->can('create-sysModule'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModule-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModule" role="tab" aria-controls="sysColor-hasmany-tabs-sysModule" aria-selected="false">
                                <i class="nav-icon fas fa-box"></i>
                                {{ucfirst(__('Core::sysModule.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatRealisationCompetences?->count() > 0 || auth()->user()?->can('create-etatRealisationCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationCompetence-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationCompetence" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgApprentissage::etatRealisationCompetence.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatRealisationMicroCompetences?->count() > 0 || auth()->user()?->can('create-etatRealisationMicroCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationMicroCompetence-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationMicroCompetence" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationMicroCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgApprentissage::etatRealisationMicroCompetence.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatsRealisationProjets?->count() > 0 || auth()->user()?->can('create-etatsRealisationProjet'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatsRealisationProjet-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatsRealisationProjet" role="tab" aria-controls="sysColor-hasmany-tabs-etatsRealisationProjet" aria-selected="false">
                                <i class="nav-icon fas fa-check"></i>
                                {{ucfirst(__('PkgRealisationProjets::etatsRealisationProjet.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatRealisationModules?->count() > 0 || auth()->user()?->can('create-etatRealisationModule'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationModule-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationModule" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationModule" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgApprentissage::etatRealisationModule.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->etatRealisationUas?->count() > 0 || auth()->user()?->can('create-etatRealisationUa'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationUa-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationUa" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationUa" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgApprentissage::etatRealisationUa.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->sectionWidgets?->count() > 0 || auth()->user()?->can('create-sectionWidget'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sectionWidget-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sectionWidget" role="tab" aria-controls="sysColor-hasmany-tabs-sectionWidget" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgWidgets::sectionWidget.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->widgets?->count() > 0 || auth()->user()?->can('create-widget'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-widget-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-widget" role="tab" aria-controls="sysColor-hasmany-tabs-widget" aria-selected="false">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                {{ucfirst(__('PkgWidgets::widget.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemSysColor->workflowTaches?->count() > 0 || auth()->user()?->can('create-workflowTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-workflowTache-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-workflowTache" role="tab" aria-controls="sysColor-hasmany-tabs-workflowTache" aria-selected="false">
                                <i class="nav-icon fas fa-check-square"></i>
                                {{ucfirst(__('PkgRealisationTache::workflowTache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysColor-tabContent">
                            <div class="tab-pane fade show active" id="sysColor-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-home-tab">
                                @include('Core::sysColor._fields')
                            </div>

                            @if($itemSysColor->etatRealisationTaches?->count() > 0 || auth()->user()?->can('create-etatRealisationTache'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationTache" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationTache-tab">
                                @include('PkgRealisationTache::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->sysModels?->count() > 0 || auth()->user()?->can('create-sysModel'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModel" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModel-tab">
                                @include('Core::sysModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatEvaluationProjets?->count() > 0 || auth()->user()?->can('create-etatEvaluationProjet'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatEvaluationProjet" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatEvaluationProjet-tab">
                                @include('PkgEvaluateurs::etatEvaluationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatRealisationChapitres?->count() > 0 || auth()->user()?->can('create-etatRealisationChapitre'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationChapitre" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationChapitre-tab">
                                @include('PkgApprentissage::etatRealisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->sysModules?->count() > 0 || auth()->user()?->can('create-sysModule'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModule" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModule-tab">
                                @include('Core::sysModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatRealisationCompetences?->count() > 0 || auth()->user()?->can('create-etatRealisationCompetence'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationCompetence" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationCompetence-tab">
                                @include('PkgApprentissage::etatRealisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatRealisationMicroCompetences?->count() > 0 || auth()->user()?->can('create-etatRealisationMicroCompetence'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationMicroCompetence" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationMicroCompetence-tab">
                                @include('PkgApprentissage::etatRealisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatsRealisationProjets?->count() > 0 || auth()->user()?->can('create-etatsRealisationProjet'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatsRealisationProjet" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatsRealisationProjet-tab">
                                @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatRealisationModules?->count() > 0 || auth()->user()?->can('create-etatRealisationModule'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationModule" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationModule-tab">
                                @include('PkgApprentissage::etatRealisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->etatRealisationUas?->count() > 0 || auth()->user()?->can('create-etatRealisationUa'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationUa" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationUa-tab">
                                @include('PkgApprentissage::etatRealisationUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->sectionWidgets?->count() > 0 || auth()->user()?->can('create-sectionWidget'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sectionWidget" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sectionWidget-tab">
                                @include('PkgWidgets::sectionWidget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->widgets?->count() > 0 || auth()->user()?->can('create-widget'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-widget" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            @endif
                            @if($itemSysColor->workflowTaches?->count() > 0 || auth()->user()?->can('create-workflowTache'))
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-workflowTache" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-workflowTache-tab">
                                @include('PkgRealisationTache::workflowTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
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
