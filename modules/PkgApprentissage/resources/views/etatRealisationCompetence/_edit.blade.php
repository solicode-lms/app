{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationCompetence',
        contextKey: 'etatRealisationCompetence.edit_{{ $itemEtatRealisationCompetence->id}}',
        cardTabSelector: '#card-tab-etatRealisationCompetence', 
        formSelector: '#etatRealisationCompetenceForm',
        editUrl: '{{ route('etatRealisationCompetences.edit',  ['etatRealisationCompetence' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationCompetence.singular") }} - {{ $itemEtatRealisationCompetence }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatRealisationCompetence }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatRealisationCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationCompetence-hasmany-tabs-home" role="tab" aria-controls="etatRealisationCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::etatRealisationCompetence.singular')}}</a>
                        </li>

                         @if($itemEtatRealisationCompetence->realisationCompetences->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationCompetence-hasmany-tabs-realisationCompetence-tab" data-toggle="pill" href="#etatRealisationCompetence-hasmany-tabs-realisationCompetence" role="tab" aria-controls="etatRealisationCompetence-hasmany-tabs-realisationCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-award"></i>
                                {{ucfirst(__('PkgApprentissage::realisationCompetence.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationCompetence-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationCompetence-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::etatRealisationCompetence._fields')
                            </div>

                            @if($itemEtatRealisationCompetence->realisationCompetences->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                            <div class="tab-pane fade" id="etatRealisationCompetence-hasmany-tabs-realisationCompetence" role="tabpanel" aria-labelledby="etatRealisationCompetence-hasmany-tabs-realisationCompetence-tab">
                                @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationCompetence.edit_' . $itemEtatRealisationCompetence->id])
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
