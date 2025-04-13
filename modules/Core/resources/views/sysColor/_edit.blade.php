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
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysColor.singular") }}',
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

                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatChapitre-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatChapitre" role="tab" aria-controls="sysColor-hasmany-tabs-etatChapitre" aria-selected="false">{{__('PkgAutoformation::etatChapitre.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatRealisationTache-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatRealisationTache" role="tab" aria-controls="sysColor-hasmany-tabs-etatRealisationTache" aria-selected="false">{{__('PkgGestionTaches::etatRealisationTache.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModel-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModel" role="tab" aria-controls="sysColor-hasmany-tabs-sysModel" aria-selected="false">{{__('Core::sysModel.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-etatFormation-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-etatFormation" role="tab" aria-controls="sysColor-hasmany-tabs-etatFormation" aria-selected="false">{{__('PkgAutoformation::etatFormation.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-labelRealisationTache-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-labelRealisationTache" role="tab" aria-controls="sysColor-hasmany-tabs-labelRealisationTache" aria-selected="false">{{__('PkgGestionTaches::labelRealisationTache.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModule-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModule" role="tab" aria-controls="sysColor-hasmany-tabs-sysModule" aria-selected="false">{{__('Core::sysModule.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sectionWidget-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sectionWidget" role="tab" aria-controls="sysColor-hasmany-tabs-sectionWidget" aria-selected="false">{{__('PkgWidgets::sectionWidget.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-widget-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-widget" role="tab" aria-controls="sysColor-hasmany-tabs-widget" aria-selected="false">{{__('PkgWidgets::widget.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-workflowChapitre-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-workflowChapitre" role="tab" aria-controls="sysColor-hasmany-tabs-workflowChapitre" aria-selected="false">{{__('PkgAutoformation::workflowChapitre.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-workflowFormation-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-workflowFormation" role="tab" aria-controls="sysColor-hasmany-tabs-workflowFormation" aria-selected="false">{{__('PkgAutoformation::workflowFormation.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-workflowProjet-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-workflowProjet" role="tab" aria-controls="sysColor-hasmany-tabs-workflowProjet" aria-selected="false">{{__('PkgRealisationProjets::workflowProjet.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysColor-tabContent">
                            <div class="tab-pane fade show active" id="sysColor-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-home-tab">
                                @include('Core::sysColor._fields')
                            </div>

                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatChapitre" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatChapitre-tab">
                                @include('PkgAutoformation::etatChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatRealisationTache" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatRealisationTache-tab">
                                @include('PkgGestionTaches::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModel" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModel-tab">
                                @include('Core::sysModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-etatFormation" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-etatFormation-tab">
                                @include('PkgAutoformation::etatFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-labelRealisationTache" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-labelRealisationTache-tab">
                                @include('PkgGestionTaches::labelRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModule" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModule-tab">
                                @include('Core::sysModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sectionWidget" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sectionWidget-tab">
                                @include('PkgWidgets::sectionWidget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-widget" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-workflowChapitre" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-workflowChapitre-tab">
                                @include('PkgAutoformation::workflowChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-workflowFormation" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-workflowFormation-tab">
                                @include('PkgAutoformation::workflowFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-workflowProjet" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-workflowProjet-tab">
                                @include('PkgRealisationProjets::workflowProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
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
