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

                         @if($itemRealisationTache->historiqueRealisationTaches?->count() > 0 || auth()->user()?->can('create-historiqueRealisationTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-historiqueRealisationTache-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-historiqueRealisationTache" role="tab" aria-controls="realisationTache-hasmany-tabs-historiqueRealisationTache" aria-selected="false">
                                <i class="nav-icon fas fa-history"></i>
                                {{ucfirst(__('PkgRealisationTache::historiqueRealisationTache.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemRealisationTache->liveCodings?->count() > 0 || auth()->user()?->can('create-liveCoding'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-liveCoding-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-liveCoding" role="tab" aria-controls="realisationTache-hasmany-tabs-liveCoding" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgRealisationTache::liveCoding.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemRealisationTache->remediations?->count() > 0 || auth()->user()?->can('create-remediation'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-remediation-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-remediation" role="tab" aria-controls="realisationTache-hasmany-tabs-remediation" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgTableauBord::remediation.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemRealisationTache->revisionTaches?->count() > 0 || auth()->user()?->can('create-revisionTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-revisionTache-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-revisionTache" role="tab" aria-controls="realisationTache-hasmany-tabs-revisionTache" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgRealisationTache::revisionTache.plural'))}}
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

                            @if($itemRealisationTache->historiqueRealisationTaches?->count() > 0 || auth()->user()?->can('create-historiqueRealisationTache'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-historiqueRealisationTache" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-historiqueRealisationTache-tab">
                                @include('PkgRealisationTache::historiqueRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
                            </div>
                            @endif
                            @if($itemRealisationTache->liveCodings?->count() > 0 || auth()->user()?->can('create-liveCoding'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-liveCoding" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-liveCoding-tab">
                                @include('PkgRealisationTache::liveCoding._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
                            </div>
                            @endif
                            @if($itemRealisationTache->remediations?->count() > 0 || auth()->user()?->can('create-remediation'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-remediation" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-remediation-tab">
                                @include('PkgTableauBord::remediation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
                            </div>
                            @endif
                            @if($itemRealisationTache->revisionTaches?->count() > 0 || auth()->user()?->can('create-revisionTache'))
                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-revisionTache" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-revisionTache-tab">
                                @include('PkgRealisationTache::revisionTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
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
