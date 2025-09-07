{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'microCompetence',
        contextKey: 'microCompetence.edit_{{ $itemMicroCompetence->id}}',
        cardTabSelector: '#card-tab-microCompetence', 
        formSelector: '#microCompetenceForm',
        editUrl: '{{ route('microCompetences.edit',  ['microCompetence' => ':id']) }}',
        indexUrl: '{{ route('microCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::microCompetence.singular") }} - {{ $itemMicroCompetence }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemMicroCompetence }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-microCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-microCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-book"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="microCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#microCompetence-hasmany-tabs-home" role="tab" aria-controls="microCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::microCompetence.singular')}}</a>
                        </li>

                         @if($itemMicroCompetence->uniteApprentissages?->count() > 0 || auth()->user()?->can('create-uniteApprentissage'))
                        <li class="nav-item">
                            <a class="nav-link" id="microCompetence-hasmany-tabs-uniteApprentissage-tab" data-toggle="pill" href="#microCompetence-hasmany-tabs-uniteApprentissage" role="tab" aria-controls="microCompetence-hasmany-tabs-uniteApprentissage" aria-selected="false">
                                <i class="nav-icon fas fa-puzzle-piece"></i>
                                {{ucfirst(__('PkgCompetences::uniteApprentissage.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemMicroCompetence->realisationMicroCompetences?->count() > 0 || auth()->user()?->can('create-realisationMicroCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="microCompetence-hasmany-tabs-realisationMicroCompetence-tab" data-toggle="pill" href="#microCompetence-hasmany-tabs-realisationMicroCompetence" role="tab" aria-controls="microCompetence-hasmany-tabs-realisationMicroCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-certificate"></i>
                                {{ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-microCompetence-tabContent">
                            <div class="tab-pane fade show active" id="microCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="microCompetence-hasmany-tabs-home-tab">
                                @include('PkgCompetences::microCompetence._fields')
                            </div>

                            @if($itemMicroCompetence->uniteApprentissages?->count() > 0 || auth()->user()?->can('create-uniteApprentissage'))
                            <div class="tab-pane fade" id="microCompetence-hasmany-tabs-uniteApprentissage" role="tabpanel" aria-labelledby="microCompetence-hasmany-tabs-uniteApprentissage-tab">
                                @include('PkgCompetences::uniteApprentissage._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'microCompetence.edit_' . $itemMicroCompetence->id])
                            </div>
                            @endif
                            @if($itemMicroCompetence->realisationMicroCompetences?->count() > 0 || auth()->user()?->can('create-realisationMicroCompetence'))
                            <div class="tab-pane fade" id="microCompetence-hasmany-tabs-realisationMicroCompetence" role="tabpanel" aria-labelledby="microCompetence-hasmany-tabs-realisationMicroCompetence-tab">
                                @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'microCompetence.edit_' . $itemMicroCompetence->id])
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
