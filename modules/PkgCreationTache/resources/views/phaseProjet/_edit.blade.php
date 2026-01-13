{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'phaseProjet',
        contextKey: 'phaseProjet.edit_{{ $itemPhaseProjet->id}}',
        cardTabSelector: '#card-tab-phaseProjet', 
        formSelector: '#phaseProjetForm',
        editUrl: '{{ route('phaseProjets.edit',  ['phaseProjet' => ':id']) }}',
        indexUrl: '{{ route('phaseProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationTache::phaseProjet.singular") }} - {{ $itemPhaseProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemPhaseProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-phaseProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-phaseProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="phaseProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#phaseProjet-hasmany-tabs-home" role="tab" aria-controls="phaseProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationTache::phaseProjet.singular')}}</a>
                        </li>

                         @if($itemPhaseProjet->taches?->count() > 0 || auth()->user()?->can('create-tache'))
                        <li class="nav-item">
                            <a class="nav-link" id="phaseProjet-hasmany-tabs-tache-tab" data-toggle="pill" href="#phaseProjet-hasmany-tabs-tache" role="tab" aria-controls="phaseProjet-hasmany-tabs-tache" aria-selected="false">
                                <i class="nav-icon fas fa-tasks"></i>
                                {{ucfirst(__('PkgCreationTache::tache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-phaseProjet-tabContent">
                            <div class="tab-pane fade show active" id="phaseProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="phaseProjet-hasmany-tabs-home-tab">
                                @include('PkgCreationTache::phaseProjet._fields')
                            </div>

                            @if($itemPhaseProjet->taches?->count() > 0 || auth()->user()?->can('create-tache'))
                            <div class="tab-pane fade" id="phaseProjet-hasmany-tabs-tache" role="tabpanel" aria-labelledby="phaseProjet-hasmany-tabs-tache-tab">
                                @include('PkgCreationTache::tache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'phaseProjet.edit_' . $itemPhaseProjet->id])
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
