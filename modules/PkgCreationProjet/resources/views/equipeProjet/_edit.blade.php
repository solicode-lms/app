{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'equipeProjet',
        contextKey: 'equipeProjet.edit_{{ $itemEquipeProjet->id}}',
        cardTabSelector: '#card-tab-equipeProjet', 
        formSelector: '#equipeProjetForm',
        editUrl: '{{ route('equipeProjets.edit',  ['equipeProjet' => ':id']) }}',
        indexUrl: '{{ route('equipeProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::equipeProjet.singular") }} - {{ $itemEquipeProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEquipeProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-equipeProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-equipeProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-users"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="equipeProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#equipeProjet-hasmany-tabs-home" role="tab" aria-controls="equipeProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationProjet::equipeProjet.singular')}}</a>
                        </li>

                         @if($itemEquipeProjet->taches?->count() > 0 || auth()->user()?->can('create-tache'))
                        <li class="nav-item">
                            <a class="nav-link" id="equipeProjet-hasmany-tabs-tache-tab" data-toggle="pill" href="#equipeProjet-hasmany-tabs-tache" role="tab" aria-controls="equipeProjet-hasmany-tabs-tache" aria-selected="false">
                                <i class="nav-icon fas fa-tasks"></i>
                                {{ucfirst(__('PkgCreationTache::tache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-equipeProjet-tabContent">
                            <div class="tab-pane fade show active" id="equipeProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="equipeProjet-hasmany-tabs-home-tab">
                                @include('PkgCreationProjet::equipeProjet._fields')
                            </div>

                            @if($itemEquipeProjet->taches?->count() > 0 || auth()->user()?->can('create-tache'))
                            <div class="tab-pane fade" id="equipeProjet-hasmany-tabs-tache" role="tabpanel" aria-labelledby="equipeProjet-hasmany-tabs-tache-tab">
                                @include('PkgCreationTache::tache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'equipeProjet.edit_' . $itemEquipeProjet->id])
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
