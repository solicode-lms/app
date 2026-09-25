{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationQcm',
        contextKey: 'realisationQcm.edit_{{ $itemRealisationQcm->id}}',
        cardTabSelector: '#card-tab-realisationQcm', 
        formSelector: '#realisationQcmForm',
        editUrl: '{{ route('realisationQcms.edit',  ['realisationQcm' => ':id']) }}',
        indexUrl: '{{ route('realisationQcms.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::realisationQcm.singular") }} - {{ $itemRealisationQcm }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemRealisationQcm }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationQcm" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationQcm-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-flask"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationQcm-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationQcm-hasmany-tabs-home" role="tab" aria-controls="realisationQcm-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::realisationQcm.singular')}}</a>
                        </li>

                         @if($itemRealisationQcm->reponseQcms?->count() > 0 || auth()->user()?->can('create-reponseQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationQcm-hasmany-tabs-reponseQcm-tab" data-toggle="pill" href="#realisationQcm-hasmany-tabs-reponseQcm" role="tab" aria-controls="realisationQcm-hasmany-tabs-reponseQcm" aria-selected="false">
                                <i class="nav-icon fas fa-check-double"></i>
                                {{ucfirst(__('PkgQcm::reponseQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationQcm-tabContent">
                            <div class="tab-pane fade show active" id="realisationQcm-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationQcm-hasmany-tabs-home-tab">
                                @include('PkgQcm::realisationQcm._fields')
                            </div>

                            @if($itemRealisationQcm->reponseQcms?->count() > 0 || auth()->user()?->can('create-reponseQcm'))
                            <div class="tab-pane fade" id="realisationQcm-hasmany-tabs-reponseQcm" role="tabpanel" aria-labelledby="realisationQcm-hasmany-tabs-reponseQcm-tab">
                                @include('PkgQcm::reponseQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationQcm.edit_' . $itemRealisationQcm->id])
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
