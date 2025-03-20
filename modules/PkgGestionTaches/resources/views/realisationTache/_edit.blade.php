{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationTache',
        contextKey: 'realisationTache.edit_{{ $itemRealisationTache->id}}',
        cardTabSelector: '#card-tab-realisationTache', 
        formSelector: '#realisationTacheForm',
        editUrl: '{{ route('realisationTaches.edit',  ['realisationTache' => ':id']) }}',
        indexUrl: '{{ route('realisationTaches.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::realisationTache.singular") }}',
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
                <div id="card-tab-realisationTache" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationTache-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-laptop-code"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationTache-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-home" role="tab" aria-controls="realisationTache-hasmany-tabs-home" aria-selected="true">{{__('PkgGestionTaches::realisationTache.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="realisationTache-hasmany-tabs-historiqueRealisationTache-tab" data-toggle="pill" href="#realisationTache-hasmany-tabs-historiqueRealisationTache" role="tab" aria-controls="realisationTache-hasmany-tabs-historiqueRealisationTache" aria-selected="false">{{__('PkgGestionTaches::historiqueRealisationTache.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationTache-tabContent">
                            <div class="tab-pane fade show active" id="realisationTache-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-home-tab">
                                @include('PkgGestionTaches::realisationTache._fields')
                            </div>

                            <div class="tab-pane fade" id="realisationTache-hasmany-tabs-historiqueRealisationTache" role="tabpanel" aria-labelledby="realisationTache-hasmany-tabs-historiqueRealisationTache-tab">
                                @include('PkgGestionTaches::historiqueRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationTache.edit_' . $itemRealisationTache->id])
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
