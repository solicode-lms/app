{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'tache',
        contextKey: 'tache.edit_{{ $itemTache->id}}',
        cardTabSelector: '#card-tab-tache', 
        formSelector: '#tacheForm',
        editUrl: '{{ route('taches.edit',  ['tache' => ':id']) }}',
        indexUrl: '{{ route('taches.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationTache::tache.singular") }} - {{ $itemTache }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemTache }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-tache" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-tache-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-tasks"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="tache-hasmany-tabs-home-tab" data-toggle="pill" href="#tache-hasmany-tabs-home" role="tab" aria-controls="tache-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationTache::tache.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="tache-hasmany-tabs-realisationTache-tab" data-toggle="pill" href="#tache-hasmany-tabs-realisationTache" role="tab" aria-controls="tache-hasmany-tabs-realisationTache" aria-selected="false">{{ucfirst(__('PkgRealisationTache::realisationTache.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-tache-tabContent">
                            <div class="tab-pane fade show active" id="tache-hasmany-tabs-home" role="tabpanel" aria-labelledby="tache-hasmany-tabs-home-tab">
                                @include('PkgCreationTache::tache._fields')
                            </div>

                            <div class="tab-pane fade" id="tache-hasmany-tabs-realisationTache" role="tabpanel" aria-labelledby="tache-hasmany-tabs-realisationTache-tab">
                                @include('PkgRealisationTache::realisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'tache.edit_' . $itemTache->id])
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
