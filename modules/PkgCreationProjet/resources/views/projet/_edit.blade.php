{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'projet',
        contextKey: 'projet.edit_{{ $itemProjet->id}}',
        cardTabSelector: '#card-tab-projet', 
        formSelector: '#projetForm',
        editUrl: '{{ route('projets.edit',  ['projet' => ':id']) }}',
        indexUrl: '{{ route('projets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::projet.singular") }} - {{ $itemProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-projet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-projet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="projet-hasmany-tabs-home-tab" data-toggle="pill" href="#projet-hasmany-tabs-home" role="tab" aria-controls="projet-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationProjet::projet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-transfertCompetence-tab" data-toggle="pill" href="#projet-hasmany-tabs-transfertCompetence" role="tab" aria-controls="projet-hasmany-tabs-transfertCompetence" aria-selected="false">{{__('PkgCreationProjet::transfertCompetence.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-affectationProjet-tab" data-toggle="pill" href="#projet-hasmany-tabs-affectationProjet" role="tab" aria-controls="projet-hasmany-tabs-affectationProjet" aria-selected="false">{{__('PkgRealisationProjets::affectationProjet.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-livrable-tab" data-toggle="pill" href="#projet-hasmany-tabs-livrable" role="tab" aria-controls="projet-hasmany-tabs-livrable" aria-selected="false">{{__('PkgCreationProjet::livrable.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-tache-tab" data-toggle="pill" href="#projet-hasmany-tabs-tache" role="tab" aria-controls="projet-hasmany-tabs-tache" aria-selected="false">{{__('PkgGestionTaches::tache.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="projet-hasmany-tabs-resource-tab" data-toggle="pill" href="#projet-hasmany-tabs-resource" role="tab" aria-controls="projet-hasmany-tabs-resource" aria-selected="false">{{__('PkgCreationProjet::resource.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-projet-tabContent">
                            <div class="tab-pane fade show active" id="projet-hasmany-tabs-home" role="tabpanel" aria-labelledby="projet-hasmany-tabs-home-tab">
                                @include('PkgCreationProjet::projet._fields')
                            </div>

                            <div class="tab-pane fade" id="projet-hasmany-tabs-transfertCompetence" role="tabpanel" aria-labelledby="projet-hasmany-tabs-transfertCompetence-tab">
                                @include('PkgCreationProjet::transfertCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'projet.edit_' . $itemProjet->id])
                            </div>
                            <div class="tab-pane fade" id="projet-hasmany-tabs-affectationProjet" role="tabpanel" aria-labelledby="projet-hasmany-tabs-affectationProjet-tab">
                                @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'projet.edit_' . $itemProjet->id])
                            </div>
                            <div class="tab-pane fade" id="projet-hasmany-tabs-livrable" role="tabpanel" aria-labelledby="projet-hasmany-tabs-livrable-tab">
                                @include('PkgCreationProjet::livrable._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'projet.edit_' . $itemProjet->id])
                            </div>
                            <div class="tab-pane fade" id="projet-hasmany-tabs-tache" role="tabpanel" aria-labelledby="projet-hasmany-tabs-tache-tab">
                                @include('PkgGestionTaches::tache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'projet.edit_' . $itemProjet->id])
                            </div>
                            <div class="tab-pane fade" id="projet-hasmany-tabs-resource" role="tabpanel" aria-labelledby="projet-hasmany-tabs-resource-tab">
                                @include('PkgCreationProjet::resource._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'projet.edit_' . $itemProjet->id])
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
