{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'formateur',
        contextKey: 'formateur.edit_{{ $itemFormateur->id}}',
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
    window.viewState = @json($viewState);
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
                                <i class="nav-icon fas fa-user-tie"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="formateur-hasmany-tabs-home-tab" data-toggle="pill" href="#formateur-hasmany-tabs-home" role="tab" aria-controls="formateur-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::formateur.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-chapitre-tab" data-toggle="pill" href="#formateur-hasmany-tabs-chapitre" role="tab" aria-controls="formateur-hasmany-tabs-chapitre" aria-selected="false">{{__('PkgAutoformation::chapitre.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-commentaireRealisationTache-tab" data-toggle="pill" href="#formateur-hasmany-tabs-commentaireRealisationTache" role="tab" aria-controls="formateur-hasmany-tabs-commentaireRealisationTache" aria-selected="false">{{__('PkgGestionTaches::commentaireRealisationTache.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-etatRealisationTache-tab" data-toggle="pill" href="#formateur-hasmany-tabs-etatRealisationTache" role="tab" aria-controls="formateur-hasmany-tabs-etatRealisationTache" aria-selected="false">{{__('PkgGestionTaches::etatRealisationTache.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-etatChapitre-tab" data-toggle="pill" href="#formateur-hasmany-tabs-etatChapitre" role="tab" aria-controls="formateur-hasmany-tabs-etatChapitre" aria-selected="false">{{__('PkgAutoformation::etatChapitre.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-etatFormation-tab" data-toggle="pill" href="#formateur-hasmany-tabs-etatFormation" role="tab" aria-controls="formateur-hasmany-tabs-etatFormation" aria-selected="false">{{__('PkgAutoformation::etatFormation.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-labelRealisationTache-tab" data-toggle="pill" href="#formateur-hasmany-tabs-labelRealisationTache" role="tab" aria-controls="formateur-hasmany-tabs-labelRealisationTache" aria-selected="false">{{__('PkgGestionTaches::labelRealisationTache.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-formation-tab" data-toggle="pill" href="#formateur-hasmany-tabs-formation" role="tab" aria-controls="formateur-hasmany-tabs-formation" aria-selected="false">{{__('PkgAutoformation::formation.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-prioriteTache-tab" data-toggle="pill" href="#formateur-hasmany-tabs-prioriteTache" role="tab" aria-controls="formateur-hasmany-tabs-prioriteTache" aria-selected="false">{{__('PkgGestionTaches::prioriteTache.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-formateur-tabContent">
                            <div class="tab-pane fade show active" id="formateur-hasmany-tabs-home" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-home-tab">
                                @include('PkgFormation::formateur._fields')
                            </div>

                            <div class="tab-pane fade" id="formateur-hasmany-tabs-chapitre" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-chapitre-tab">
                                @include('PkgAutoformation::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-commentaireRealisationTache" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-commentaireRealisationTache-tab">
                                @include('PkgGestionTaches::commentaireRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-etatRealisationTache" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-etatRealisationTache-tab">
                                @include('PkgGestionTaches::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-etatChapitre" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-etatChapitre-tab">
                                @include('PkgAutoformation::etatChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-etatFormation" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-etatFormation-tab">
                                @include('PkgAutoformation::etatFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-labelRealisationTache" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-labelRealisationTache-tab">
                                @include('PkgGestionTaches::labelRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-formation" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-formation-tab">
                                @include('PkgAutoformation::formation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-prioriteTache" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-prioriteTache-tab">
                                @include('PkgGestionTaches::prioriteTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
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
