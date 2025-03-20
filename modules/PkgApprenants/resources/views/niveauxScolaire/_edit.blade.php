{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'niveauxScolaire',
        contextKey: 'niveauxScolaire.edit_{{ $itemNiveauxScolaire->id}}',
        cardTabSelector: '#card-tab-niveauxScolaire', 
        formSelector: '#niveauxScolaireForm',
        editUrl: '{{ route('niveauxScolaires.edit',  ['niveauxScolaire' => ':id']) }}',
        indexUrl: '{{ route('niveauxScolaires.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::niveauxScolaire.singular") }}',
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
                <div id="card-tab-niveauxScolaire" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-niveauxScolaire-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-award"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="niveauxScolaire-hasmany-tabs-home-tab" data-toggle="pill" href="#niveauxScolaire-hasmany-tabs-home" role="tab" aria-controls="niveauxScolaire-hasmany-tabs-home" aria-selected="true">{{__('PkgApprenants::niveauxScolaire.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="niveauxScolaire-hasmany-tabs-apprenant-tab" data-toggle="pill" href="#niveauxScolaire-hasmany-tabs-apprenant" role="tab" aria-controls="niveauxScolaire-hasmany-tabs-apprenant" aria-selected="false">{{__('PkgApprenants::apprenant.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-niveauxScolaire-tabContent">
                            <div class="tab-pane fade show active" id="niveauxScolaire-hasmany-tabs-home" role="tabpanel" aria-labelledby="niveauxScolaire-hasmany-tabs-home-tab">
                                @include('PkgApprenants::niveauxScolaire._fields')
                            </div>

                            <div class="tab-pane fade" id="niveauxScolaire-hasmany-tabs-apprenant" role="tabpanel" aria-labelledby="niveauxScolaire-hasmany-tabs-apprenant-tab">
                                @include('PkgApprenants::apprenant._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'niveauxScolaire.edit_' . $itemNiveauxScolaire->id])
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
