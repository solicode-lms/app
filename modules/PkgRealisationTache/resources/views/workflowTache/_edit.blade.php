{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'workflowTache',
        contextKey: 'workflowTache.edit_{{ $itemWorkflowTache->id}}',
        cardTabSelector: '#card-tab-workflowTache', 
        formSelector: '#workflowTacheForm',
        editUrl: '{{ route('workflowTaches.edit',  ['workflowTache' => ':id']) }}',
        indexUrl: '{{ route('workflowTaches.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationTache::workflowTache.singular") }} - {{ $itemWorkflowTache }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemWorkflowTache }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-workflowTache" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-workflowTache-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="workflowTache-hasmany-tabs-home-tab" data-toggle="pill" href="#workflowTache-hasmany-tabs-home" role="tab" aria-controls="workflowTache-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationTache::workflowTache.singular')}}</a>
                        </li>

                         @if($itemRealisationTache->etatRealisationTaches->count() > 0 || auth()->user()?->can('create-etatRealisationTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="workflowTache-hasmany-tabs-etatRealisationTache-tab" data-toggle="pill" href="#workflowTache-hasmany-tabs-etatRealisationTache" role="tab" aria-controls="workflowTache-hasmany-tabs-etatRealisationTache" aria-selected="false">
                                <i class="nav-icon fas fa-check"></i>
                                {{ucfirst(__('PkgRealisationTache::etatRealisationTache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-workflowTache-tabContent">
                            <div class="tab-pane fade show active" id="workflowTache-hasmany-tabs-home" role="tabpanel" aria-labelledby="workflowTache-hasmany-tabs-home-tab">
                                @include('PkgRealisationTache::workflowTache._fields')
                            </div>

                            @if($itemRealisationTache->etatRealisationTaches->count() > 0 || auth()->user()?->can('create-etatRealisationTache'))
                            <div class="tab-pane fade" id="workflowTache-hasmany-tabs-etatRealisationTache" role="tabpanel" aria-labelledby="workflowTache-hasmany-tabs-etatRealisationTache-tab">
                                @include('PkgRealisationTache::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'workflowTache.edit_' . $itemWorkflowTache->id])
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
