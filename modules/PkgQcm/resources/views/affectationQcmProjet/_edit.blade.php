{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'affectationQcmProjet',
        contextKey: 'affectationQcmProjet.edit_{{ $itemAffectationQcmProjet->id}}',
        cardTabSelector: '#card-tab-affectationQcmProjet', 
        formSelector: '#affectationQcmProjetForm',
        editUrl: '{{ route('affectationQcmProjets.edit',  ['affectationQcmProjet' => ':id']) }}',
        indexUrl: '{{ route('affectationQcmProjets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::affectationQcmProjet.singular") }} - {{ $itemAffectationQcmProjet }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemAffectationQcmProjet }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-affectationQcmProjet" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-affectationQcmProjet-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-desktop"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="affectationQcmProjet-hasmany-tabs-home-tab" data-toggle="pill" href="#affectationQcmProjet-hasmany-tabs-home" role="tab" aria-controls="affectationQcmProjet-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::affectationQcmProjet.singular')}}</a>
                        </li>

                         @if($itemAffectationQcmProjet->realisationQcms?->count() > 0 || auth()->user()?->can('create-realisationQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="affectationQcmProjet-hasmany-tabs-realisationQcm-tab" data-toggle="pill" href="#affectationQcmProjet-hasmany-tabs-realisationQcm" role="tab" aria-controls="affectationQcmProjet-hasmany-tabs-realisationQcm" aria-selected="false">
                                <i class="nav-icon fas fa-cog"></i>
                                {{ucfirst(__('PkgQcm::realisationQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-affectationQcmProjet-tabContent">
                            <div class="tab-pane fade show active" id="affectationQcmProjet-hasmany-tabs-home" role="tabpanel" aria-labelledby="affectationQcmProjet-hasmany-tabs-home-tab">
                                @include('PkgQcm::affectationQcmProjet._fields')
                            </div>

                            @if($itemAffectationQcmProjet->realisationQcms?->count() > 0 || auth()->user()?->can('create-realisationQcm'))
                            <div class="tab-pane fade" id="affectationQcmProjet-hasmany-tabs-realisationQcm" role="tabpanel" aria-labelledby="affectationQcmProjet-hasmany-tabs-realisationQcm-tab">
                                @include('PkgQcm::realisationQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'affectationQcmProjet.edit_' . $itemAffectationQcmProjet->id])
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
