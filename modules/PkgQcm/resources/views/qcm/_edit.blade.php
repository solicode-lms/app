{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'qcm',
        contextKey: 'qcm.edit_{{ $itemQcm->id}}',
        cardTabSelector: '#card-tab-qcm', 
        formSelector: '#qcmForm',
        editUrl: '{{ route('qcms.edit',  ['qcm' => ':id']) }}',
        indexUrl: '{{ route('qcms.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::qcm.singular") }} - {{ $itemQcm }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemQcm }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-qcm" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-qcm-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="qcm-hasmany-tabs-home-tab" data-toggle="pill" href="#qcm-hasmany-tabs-home" role="tab" aria-controls="qcm-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::qcm.singular')}}</a>
                        </li>

                         @if($itemQcm->affectationQcmProjets?->count() > 0 || auth()->user()?->can('create-affectationQcmProjet'))
                        <li class="nav-item">
                            <a class="nav-link" id="qcm-hasmany-tabs-affectationQcmProjet-tab" data-toggle="pill" href="#qcm-hasmany-tabs-affectationQcmProjet" role="tab" aria-controls="qcm-hasmany-tabs-affectationQcmProjet" aria-selected="false">
                                <i class="nav-icon fas fa-desktop"></i>
                                {{ucfirst(__('PkgQcm::affectationQcmProjet.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemQcm->questionQcms?->count() > 0 || auth()->user()?->can('create-questionQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="qcm-hasmany-tabs-questionQcm-tab" data-toggle="pill" href="#qcm-hasmany-tabs-questionQcm" role="tab" aria-controls="qcm-hasmany-tabs-questionQcm" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgQcm::questionQcm.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemQcm->realisationQcms?->count() > 0 || auth()->user()?->can('create-realisationQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="qcm-hasmany-tabs-realisationQcm-tab" data-toggle="pill" href="#qcm-hasmany-tabs-realisationQcm" role="tab" aria-controls="qcm-hasmany-tabs-realisationQcm" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgQcm::realisationQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-qcm-tabContent">
                            <div class="tab-pane fade show active" id="qcm-hasmany-tabs-home" role="tabpanel" aria-labelledby="qcm-hasmany-tabs-home-tab">
                                @include('PkgQcm::qcm._fields')
                            </div>

                            @if($itemQcm->affectationQcmProjets?->count() > 0 || auth()->user()?->can('create-affectationQcmProjet'))
                            <div class="tab-pane fade" id="qcm-hasmany-tabs-affectationQcmProjet" role="tabpanel" aria-labelledby="qcm-hasmany-tabs-affectationQcmProjet-tab">
                                @include('PkgQcm::affectationQcmProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'qcm.edit_' . $itemQcm->id])
                            </div>
                            @endif
                            @if($itemQcm->questionQcms?->count() > 0 || auth()->user()?->can('create-questionQcm'))
                            <div class="tab-pane fade" id="qcm-hasmany-tabs-questionQcm" role="tabpanel" aria-labelledby="qcm-hasmany-tabs-questionQcm-tab">
                                @include('PkgQcm::questionQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'qcm.edit_' . $itemQcm->id])
                            </div>
                            @endif
                            @if($itemQcm->realisationQcms?->count() > 0 || auth()->user()?->can('create-realisationQcm'))
                            <div class="tab-pane fade" id="qcm-hasmany-tabs-realisationQcm" role="tabpanel" aria-labelledby="qcm-hasmany-tabs-realisationQcm-tab">
                                @include('PkgQcm::realisationQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'qcm.edit_' . $itemQcm->id])
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
