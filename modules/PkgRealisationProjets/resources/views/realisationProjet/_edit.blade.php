{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationProjet',
        contextKey: 'realisationProjet.edit_{{ $itemRealisationProjet->id}}',
        cardTabSelector: '#card-tab-realisationProjet', 
        formSelector: '#realisationProjetForm',
        editUrl: '{{ route('realisationProjets.edit',  ['realisationProjet' => ':id']) }}',
        indexUrl: '{{ route('realisationProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::realisationProjet.singular") }} - {{ $itemRealisationProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemRealisationProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-laptop"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationProjet-hasmany-tabs-home" role="tab" aria-controls="realisationProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationProjets::realisationProjet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="realisationProjet-hasmany-tabs-realisationTache-tab" data-toggle="pill" href="#realisationProjet-hasmany-tabs-realisationTache" role="tab" aria-controls="realisationProjet-hasmany-tabs-realisationTache" aria-selected="false">{{ucfirst(__('PkgGestionTaches::realisationTache.plural'))}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="realisationProjet-hasmany-tabs-livrablesRealisation-tab" data-toggle="pill" href="#realisationProjet-hasmany-tabs-livrablesRealisation" role="tab" aria-controls="realisationProjet-hasmany-tabs-livrablesRealisation" aria-selected="false">{{ucfirst(__('PkgRealisationProjets::livrablesRealisation.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationProjet-tabContent">
                            <div class="tab-pane fade show active" id="realisationProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationProjet-hasmany-tabs-home-tab">
                                @include('PkgRealisationProjets::realisationProjet._fields')
                            </div>

                            <div class="tab-pane fade" id="realisationProjet-hasmany-tabs-realisationTache" role="tabpanel" aria-labelledby="realisationProjet-hasmany-tabs-realisationTache-tab">
                                @include('PkgGestionTaches::realisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationProjet.edit_' . $itemRealisationProjet->id])
                            </div>
                            <div class="tab-pane fade" id="realisationProjet-hasmany-tabs-livrablesRealisation" role="tabpanel" aria-labelledby="realisationProjet-hasmany-tabs-livrablesRealisation-tab">
                                @include('PkgRealisationProjets::livrablesRealisation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationProjet.edit_' . $itemRealisationProjet->id])
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
