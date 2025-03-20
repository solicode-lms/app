{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationFormation',
        contextKey: 'realisationFormation.edit_{{ $itemRealisationFormation->id}}',
        cardTabSelector: '#card-tab-realisationFormation', 
        formSelector: '#realisationFormationForm',
        editUrl: '{{ route('realisationFormations.edit',  ['realisationFormation' => ':id']) }}',
        indexUrl: '{{ route('realisationFormations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::realisationFormation.singular") }}',
    });
</script>
<script>
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationFormation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationFormation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-coffee"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationFormation-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationFormation-hasmany-tabs-home" role="tab" aria-controls="realisationFormation-hasmany-tabs-home" aria-selected="true">{{__('PkgAutoformation::realisationFormation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="realisationFormation-hasmany-tabs-realisationChapitre-tab" data-toggle="pill" href="#realisationFormation-hasmany-tabs-realisationChapitre" role="tab" aria-controls="realisationFormation-hasmany-tabs-realisationChapitre" aria-selected="false">{{__('PkgAutoformation::realisationChapitre.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationFormation-tabContent">
                            <div class="tab-pane fade show active" id="realisationFormation-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationFormation-hasmany-tabs-home-tab">
                                @include('PkgAutoformation::realisationFormation._fields')
                            </div>

                            <div class="tab-pane fade" id="realisationFormation-hasmany-tabs-realisationChapitre" role="tabpanel" aria-labelledby="realisationFormation-hasmany-tabs-realisationChapitre-tab">
                                @include('PkgAutoformation::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationFormation.edit_' . $itemRealisationFormation->id])
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
