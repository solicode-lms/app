{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationTache',
        contextKey: 'realisationTache.edit_{{ $itemRealisationTache->id}}',
        cardTabSelector: '#card-tab-realisationTache', 
        formSelector: '#realisationTacheForm',
        editUrl: '{{ route('realisationTaches.edit',  ['realisationTache' => ':id']) }}',
        indexUrl: '{{ route('realisationTaches.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationTache::realisationTache.singular") }} - {{ $itemRealisationTache }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemRealisationTache }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationTache" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationTache-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-laptop-code"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationTache-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-home" role="tab" aria-controls="realisationTache-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationTache::realisationTache.singular')}}</a>
                        </li>

                         @if($itemRealisationTache->realisationChapitres->count() > 0 || auth()->user()?->can('create-realisationChapitre'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-realisationChapitre-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-realisationChapitre" role="tab" aria-controls="realisationTache-hasmany-tabs-realisationChapitre" aria-selected="false">
                                <i class="nav-icon fas fa-code"></i>
                                {{ucfirst(__('PkgApprentissage::realisationChapitre.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemRealisationTache->realisationUaProjets->count() > 0 || auth()->user()?->can('create-realisationUaProjet'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-realisationUaProjet-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-realisationUaProjet" role="tab" aria-controls="realisationTache-hasmany-tabs-realisationUaProjet" aria-selected="false">
                                <i class="nav-icon fas fa-cogs"></i>
                                {{ucfirst(__('PkgApprentissage::realisationUaProjet.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemRealisationTache->realisationUaPrototypes->count() > 0 || auth()->user()?->can('create-realisationUaPrototype'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-realisationUaPrototype-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-realisationUaPrototype" role="tab" aria-controls="realisationTache-hasmany-tabs-realisationUaPrototype" aria-selected="false">
                                <i class="nav-icon fas fa-cog"></i>
                                {{ucfirst(__('PkgApprentissage::realisationUaPrototype.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationTache-tabContent">
                            <div class="tab-pane fade show active" id="realisationTache-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-home-tab">
                                @include('PkgRealisationTache::realisationTache._fields')
                            </div>

                            @if($itemRealisationTache->realisationChapitres->count() > 0 || auth()->user()?->can('create-realisationChapitre'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-realisationChapitre" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-realisationChapitre-tab">
                                @include('PkgApprentissage::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
                            </div>
                            @endif
                            @if($itemRealisationTache->realisationUaProjets->count() > 0 || auth()->user()?->can('create-realisationUaProjet'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-realisationUaProjet" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-realisationUaProjet-tab">
                                @include('PkgApprentissage::realisationUaProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
                            </div>
                            @endif
                            @if($itemRealisationTache->realisationUaPrototypes->count() > 0 || auth()->user()?->can('create-realisationUaPrototype'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-realisationUaPrototype" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-realisationUaPrototype-tab">
                                @include('PkgApprentissage::realisationUaPrototype._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
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
