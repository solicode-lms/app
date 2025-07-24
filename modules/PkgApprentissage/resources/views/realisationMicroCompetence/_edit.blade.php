{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationMicroCompetence',
        contextKey: 'realisationMicroCompetence.edit_{{ $itemRealisationMicroCompetence->id}}',
        cardTabSelector: '#card-tab-realisationMicroCompetence', 
        formSelector: '#realisationMicroCompetenceForm',
        editUrl: '{{ route('realisationMicroCompetences.edit',  ['realisationMicroCompetence' => ':id']) }}',
        indexUrl: '{{ route('realisationMicroCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationMicroCompetence.singular") }} - {{ $itemRealisationMicroCompetence }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemRealisationMicroCompetence }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationMicroCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationMicroCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationMicroCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationMicroCompetence-hasmany-tabs-home" role="tab" aria-controls="realisationMicroCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::realisationMicroCompetence.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="realisationMicroCompetence-hasmany-tabs-realisationUa-tab" data-toggle="pill" href="#realisationMicroCompetence-hasmany-tabs-realisationUa" role="tab" aria-controls="realisationMicroCompetence-hasmany-tabs-realisationUa" aria-selected="false">{{ucfirst(__('PkgApprentissage::realisationUa.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationMicroCompetence-tabContent">
                            <div class="tab-pane fade show active" id="realisationMicroCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationMicroCompetence-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::realisationMicroCompetence._fields')
                            </div>

                            <div class="tab-pane fade" id="realisationMicroCompetence-hasmany-tabs-realisationUa" role="tabpanel" aria-labelledby="realisationMicroCompetence-hasmany-tabs-realisationUa-tab">
                                @include('PkgApprentissage::realisationUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationMicroCompetence.edit_' . $itemRealisationMicroCompetence->id])
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
