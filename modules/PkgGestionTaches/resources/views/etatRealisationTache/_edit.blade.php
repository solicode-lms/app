{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationTache',
        contextKey: 'etatRealisationTache.edit_{{ $itemEtatRealisationTache->id}}',
        cardTabSelector: '#card-tab-etatRealisationTache', 
        formSelector: '#etatRealisationTacheForm',
        editUrl: '{{ route('etatRealisationTaches.edit',  ['etatRealisationTache' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationTaches.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::etatRealisationTache.singular") }}',
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
                <div id="card-tab-etatRealisationTache" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationTache-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationTache-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationTache-hasmany-tabs-home" role="tab" aria-controls="etatRealisationTache-hasmany-tabs-home" aria-selected="true">{{__('PkgGestionTaches::etatRealisationTache.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationTache-hasmany-tabs-realisationTache-tab" data-toggle="pill" href="#etatRealisationTache-hasmany-tabs-realisationTache" role="tab" aria-controls="etatRealisationTache-hasmany-tabs-realisationTache" aria-selected="false">{{__('PkgGestionTaches::realisationTache.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationTache-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationTache-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationTache-hasmany-tabs-home-tab">
                                @include('PkgGestionTaches::etatRealisationTache._fields')
                            </div>

                            <div class="tab-pane fade" id="etatRealisationTache-hasmany-tabs-realisationTache" role="tabpanel" aria-labelledby="etatRealisationTache-hasmany-tabs-realisationTache-tab">
                                @include('PkgGestionTaches::realisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationTache.edit_' . $itemEtatRealisationTache->id])
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
