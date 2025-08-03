{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'affectationProjet',
        contextKey: 'affectationProjet.edit_{{ $itemAffectationProjet->id}}',
        cardTabSelector: '#card-tab-affectationProjet', 
        formSelector: '#affectationProjetForm',
        editUrl: '{{ route('affectationProjets.edit',  ['affectationProjet' => ':id']) }}',
        indexUrl: '{{ route('affectationProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::affectationProjet.singular") }} - {{ $itemAffectationProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemAffectationProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-affectationProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-affectationProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-calendar-check"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="affectationProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#affectationProjet-hasmany-tabs-home" role="tab" aria-controls="affectationProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationProjets::affectationProjet.singular')}}</a>
                        </li>

                         @if($itemRealisationTache->realisationProjets->count() > 0 || auth()->user()?->can('create-realisationProjet'))
                        <li class="nav-item">
                            <a class="nav-link" id="affectationProjet-hasmany-tabs-realisationProjet-tab" data-toggle="pill" href="#affectationProjet-hasmany-tabs-realisationProjet" role="tab" aria-controls="affectationProjet-hasmany-tabs-realisationProjet" aria-selected="false">
                                <i class="nav-icon fas fa-laptop"></i>
                                {{ucfirst(__('PkgRealisationProjets::realisationProjet.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-affectationProjet-tabContent">
                            <div class="tab-pane fade show active" id="affectationProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="affectationProjet-hasmany-tabs-home-tab">
                                @include('PkgRealisationProjets::affectationProjet._fields')
                            </div>

                            @if($itemRealisationTache->realisationProjets->count() > 0 || auth()->user()?->can('create-realisationProjet'))
                            <div class="tab-pane fade" id="affectationProjet-hasmany-tabs-realisationProjet" role="tabpanel" aria-labelledby="affectationProjet-hasmany-tabs-realisationProjet-tab">
                                @include('PkgRealisationProjets::realisationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'affectationProjet.edit_' . $itemAffectationProjet->id])
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
