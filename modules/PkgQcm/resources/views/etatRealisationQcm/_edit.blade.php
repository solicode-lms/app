{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationQcm',
        contextKey: 'etatRealisationQcm.edit_{{ $itemEtatRealisationQcm->id}}',
        cardTabSelector: '#card-tab-etatRealisationQcm', 
        formSelector: '#etatRealisationQcmForm',
        editUrl: '{{ route('etatRealisationQcms.edit',  ['etatRealisationQcm' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationQcms.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::etatRealisationQcm.singular") }} - {{ $itemEtatRealisationQcm }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatRealisationQcm }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatRealisationQcm" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationQcm-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationQcm-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationQcm-hasmany-tabs-home" role="tab" aria-controls="etatRealisationQcm-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::etatRealisationQcm.singular')}}</a>
                        </li>

                         @if($itemEtatRealisationQcm->realisationQcms?->count() > 0 || auth()->user()?->can('create-realisationQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationQcm-hasmany-tabs-realisationQcm-tab" data-toggle="pill" href="#etatRealisationQcm-hasmany-tabs-realisationQcm" role="tab" aria-controls="etatRealisationQcm-hasmany-tabs-realisationQcm" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgQcm::realisationQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationQcm-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationQcm-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationQcm-hasmany-tabs-home-tab">
                                @include('PkgQcm::etatRealisationQcm._fields')
                            </div>

                            @if($itemEtatRealisationQcm->realisationQcms?->count() > 0 || auth()->user()?->can('create-realisationQcm'))
                            <div class="tab-pane fade" id="etatRealisationQcm-hasmany-tabs-realisationQcm" role="tabpanel" aria-labelledby="etatRealisationQcm-hasmany-tabs-realisationQcm-tab">
                                @include('PkgQcm::realisationQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationQcm.edit_' . $itemEtatRealisationQcm->id])
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
