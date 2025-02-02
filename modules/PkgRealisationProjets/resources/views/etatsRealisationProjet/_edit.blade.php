{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatsRealisationProjet',
        cardTabSelector: '#card-tab-etatsRealisationProjet', 
        formSelector: '#etatsRealisationProjetForm',
        editUrl: '{{ route('etatsRealisationProjets.edit',  ['etatsRealisationProjet' => ':id']) }}',
        indexUrl: '{{ route('etatsRealisationProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::etatsRealisationProjet.singular") }}',
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
                <div id="card-tab-etatsRealisationProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatsRealisationProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatsRealisationProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#etatsRealisationProjet-hasmany-tabs-home" role="tab" aria-controls="etatsRealisationProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationProjets::etatsRealisationProjet.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatsRealisationProjet-hasmany-tabs-realisationProjet-tab" data-toggle="pill" href="#etatsRealisationProjet-hasmany-tabs-realisationProjet" role="tab" aria-controls="etatsRealisationProjet-hasmany-tabs-realisationProjet" aria-selected="false">{{__('PkgRealisationProjets::realisationProjet.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatsRealisationProjet-tabContent">
                            <div class="tab-pane fade show active" id="etatsRealisationProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatsRealisationProjet-hasmany-tabs-home-tab">
                                @include('PkgRealisationProjets::etatsRealisationProjet._fields')
                            </div>

                            <div class="tab-pane fade" id="etatsRealisationProjet-hasmany-tabs-realisationProjet" role="tabpanel" aria-labelledby="etatsRealisationProjet-hasmany-tabs-realisationProjet-tab">
                                @include('PkgRealisationProjets::realisationProjet._index',['isMany' => true, "edit_has_many" => false])
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
