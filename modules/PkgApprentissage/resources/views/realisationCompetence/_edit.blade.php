{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationCompetence',
        contextKey: 'realisationCompetence.edit_{{ $itemRealisationCompetence->id}}',
        cardTabSelector: '#card-tab-realisationCompetence', 
        formSelector: '#realisationCompetenceForm',
        editUrl: '{{ route('realisationCompetences.edit',  ['realisationCompetence' => ':id']) }}',
        indexUrl: '{{ route('realisationCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationCompetence.singular") }} - {{ $itemRealisationCompetence }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemRealisationCompetence }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-award"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationCompetence-hasmany-tabs-home" role="tab" aria-controls="realisationCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::realisationCompetence.singular')}}</a>
                        </li>

                         @if($itemRealisationCompetence->realisationMicroCompetences->count() > 0 || auth()->user()?->can('create-realisationMicroCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationCompetence-hasmany-tabs-realisationMicroCompetence-tab" data-toggle="pill" href="#realisationCompetence-hasmany-tabs-realisationMicroCompetence" role="tab" aria-controls="realisationCompetence-hasmany-tabs-realisationMicroCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-certificate"></i>
                                {{ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationCompetence-tabContent">
                            <div class="tab-pane fade show active" id="realisationCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationCompetence-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::realisationCompetence._fields')
                            </div>

                            @if($itemRealisationCompetence->realisationMicroCompetences->count() > 0 || auth()->user()?->can('create-realisationMicroCompetence'))
                            <div class="tab-pane fade" id="realisationCompetence-hasmany-tabs-realisationMicroCompetence" role="tabpanel" aria-labelledby="realisationCompetence-hasmany-tabs-realisationMicroCompetence-tab">
                                @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationCompetence.edit_' . $itemRealisationCompetence->id])
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
