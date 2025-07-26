{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'anneeFormation',
        contextKey: 'anneeFormation.edit_{{ $itemAnneeFormation->id}}',
        cardTabSelector: '#card-tab-anneeFormation', 
        formSelector: '#anneeFormationForm',
        editUrl: '{{ route('anneeFormations.edit',  ['anneeFormation' => ':id']) }}',
        indexUrl: '{{ route('anneeFormations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::anneeFormation.singular") }} - {{ $itemAnneeFormation }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemAnneeFormation }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-anneeFormation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-anneeFormation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-calendar-plus"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="anneeFormation-hasmany-tabs-home-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-home" role="tab" aria-controls="anneeFormation-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::anneeFormation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="anneeFormation-hasmany-tabs-affectationProjet-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-affectationProjet" role="tab" aria-controls="anneeFormation-hasmany-tabs-affectationProjet" aria-selected="false">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                {{ucfirst(__('PkgRealisationProjets::affectationProjet.plural'))}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="anneeFormation-hasmany-tabs-groupe-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-groupe" role="tab" aria-controls="anneeFormation-hasmany-tabs-groupe" aria-selected="false">
                                <i class="nav-icon fas fa-users"></i>
                                {{ucfirst(__('PkgApprenants::groupe.plural'))}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="anneeFormation-hasmany-tabs-sessionFormation-tab" data-toggle="pill" href="#anneeFormation-hasmany-tabs-sessionFormation" role="tab" aria-controls="anneeFormation-hasmany-tabs-sessionFormation" aria-selected="false">
                                <i class="nav-icon fas fa-map"></i>
                                {{ucfirst(__('PkgSessions::sessionFormation.plural'))}}
                            </a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-anneeFormation-tabContent">
                            <div class="tab-pane fade show active" id="anneeFormation-hasmany-tabs-home" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-home-tab">
                                @include('PkgFormation::anneeFormation._fields')
                            </div>

                            <div class="tab-pane fade" id="anneeFormation-hasmany-tabs-affectationProjet" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-affectationProjet-tab">
                                @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'anneeFormation.edit_' . $itemAnneeFormation->id])
                            </div>
                            <div class="tab-pane fade" id="anneeFormation-hasmany-tabs-groupe" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-groupe-tab">
                                @include('PkgApprenants::groupe._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'anneeFormation.edit_' . $itemAnneeFormation->id])
                            </div>
                            <div class="tab-pane fade" id="anneeFormation-hasmany-tabs-sessionFormation" role="tabpanel" aria-labelledby="anneeFormation-hasmany-tabs-sessionFormation-tab">
                                @include('PkgSessions::sessionFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'anneeFormation.edit_' . $itemAnneeFormation->id])
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
