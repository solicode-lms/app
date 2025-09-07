{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'tacheAffectation',
        contextKey: 'tacheAffectation.edit_{{ $itemTacheAffectation->id}}',
        cardTabSelector: '#card-tab-tacheAffectation', 
        formSelector: '#tacheAffectationForm',
        editUrl: '{{ route('tacheAffectations.edit',  ['tacheAffectation' => ':id']) }}',
        indexUrl: '{{ route('tacheAffectations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationTache::tacheAffectation.singular") }} - {{ $itemTacheAffectation }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemTacheAffectation }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-tacheAffectation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-tacheAffectation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="tacheAffectation-hasmany-tabs-home-tab" data-toggle="pill" href="#tacheAffectation-hasmany-tabs-home" role="tab" aria-controls="tacheAffectation-hasmany-tabs-home" aria-selected="true">{{__('PkgRealisationTache::tacheAffectation.singular')}}</a>
                        </li>

                         @if($itemTacheAffectation->realisationTaches?->count() > 0 || auth()->user()?->can('create-realisationTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="tacheAffectation-hasmany-tabs-realisationTache-tab" data-toggle="pill" href="#tacheAffectation-hasmany-tabs-realisationTache" role="tab" aria-controls="tacheAffectation-hasmany-tabs-realisationTache" aria-selected="false">
                                <i class="nav-icon fas fa-laptop-code"></i>
                                {{ucfirst(__('PkgRealisationTache::realisationTache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-tacheAffectation-tabContent">
                            <div class="tab-pane fade show active" id="tacheAffectation-hasmany-tabs-home" role="tabpanel" aria-labelledby="tacheAffectation-hasmany-tabs-home-tab">
                                @include('PkgRealisationTache::tacheAffectation._fields')
                            </div>

                            @if($itemTacheAffectation->realisationTaches?->count() > 0 || auth()->user()?->can('create-realisationTache'))
                            <div class="tab-pane fade" id="tacheAffectation-hasmany-tabs-realisationTache" role="tabpanel" aria-labelledby="tacheAffectation-hasmany-tabs-realisationTache-tab">
                                @include('PkgRealisationTache::realisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'tacheAffectation.edit_' . $itemTacheAffectation->id])
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
