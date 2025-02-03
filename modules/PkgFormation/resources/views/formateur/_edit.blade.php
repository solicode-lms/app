{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'formateur',
        cardTabSelector: '#card-tab-formateur', 
        formSelector: '#formateurForm',
        editUrl: '{{ route('formateurs.edit',  ['formateur' => ':id']) }}',
        indexUrl: '{{ route('formateurs.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::formateur.singular") }}',
    });
</script>
<script>
    window.contextState = @json($contextState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-formateur" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-formateur-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="formateur-hasmany-tabs-home-tab" data-toggle="pill" href="#formateur-hasmany-tabs-home" role="tab" aria-controls="formateur-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::formateur.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-etatsRealisationProjet-tab" data-toggle="pill" href="#formateur-hasmany-tabs-etatsRealisationProjet" role="tab" aria-controls="formateur-hasmany-tabs-etatsRealisationProjet" aria-selected="false">{{__('PkgRealisationProjets::etatsRealisationProjet.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-niveauDifficulte-tab" data-toggle="pill" href="#formateur-hasmany-tabs-niveauDifficulte" role="tab" aria-controls="formateur-hasmany-tabs-niveauDifficulte" aria-selected="false">{{__('PkgCompetences::niveauDifficulte.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-projet-tab" data-toggle="pill" href="#formateur-hasmany-tabs-projet" role="tab" aria-controls="formateur-hasmany-tabs-projet" aria-selected="false">{{__('PkgCreationProjet::projet.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-formateur-tabContent">
                            <div class="tab-pane fade show active" id="formateur-hasmany-tabs-home" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-home-tab">
                                @include('PkgFormation::formateur._fields')
                            </div>

                            <div class="tab-pane fade" id="formateur-hasmany-tabs-etatsRealisationProjet" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-etatsRealisationProjet-tab">
                                @include('PkgRealisationProjets::etatsRealisationProjet._index',['isMany' => true, "edit_has_many" => false])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-niveauDifficulte" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-niveauDifficulte-tab">
                                @include('PkgCompetences::niveauDifficulte._index',['isMany' => true, "edit_has_many" => false])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-projet" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-projet-tab">
                                @include('PkgCreationProjet::projet._index',['isMany' => true, "edit_has_many" => false])
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
