{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationMicroCompetence',
        contextKey: 'etatRealisationMicroCompetence.edit_{{ $itemEtatRealisationMicroCompetence->id}}',
        cardTabSelector: '#card-tab-etatRealisationMicroCompetence', 
        formSelector: '#etatRealisationMicroCompetenceForm',
        editUrl: '{{ route('etatRealisationMicroCompetences.edit',  ['etatRealisationMicroCompetence' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationMicroCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationMicroCompetence.singular") }} - {{ $itemEtatRealisationMicroCompetence }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatRealisationMicroCompetence }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatRealisationMicroCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationMicroCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationMicroCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationMicroCompetence-hasmany-tabs-home" role="tab" aria-controls="etatRealisationMicroCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::etatRealisationMicroCompetence.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationMicroCompetence-hasmany-tabs-realisationMicroCompetence-tab" data-toggle="pill" href="#etatRealisationMicroCompetence-hasmany-tabs-realisationMicroCompetence" role="tab" aria-controls="etatRealisationMicroCompetence-hasmany-tabs-realisationMicroCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-certificate"></i>
                                {{ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural'))}}
                            </a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationMicroCompetence-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationMicroCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationMicroCompetence-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::etatRealisationMicroCompetence._fields')
                            </div>

                            <div class="tab-pane fade" id="etatRealisationMicroCompetence-hasmany-tabs-realisationMicroCompetence" role="tabpanel" aria-labelledby="etatRealisationMicroCompetence-hasmany-tabs-realisationMicroCompetence-tab">
                                @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationMicroCompetence.edit_' . $itemEtatRealisationMicroCompetence->id])
                            </div>

                           
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                </div>
            </div>
        </div>
    </section>
@show
