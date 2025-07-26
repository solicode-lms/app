{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sessionFormation',
        contextKey: 'sessionFormation.edit_{{ $itemSessionFormation->id}}',
        cardTabSelector: '#card-tab-sessionFormation', 
        formSelector: '#sessionFormationForm',
        editUrl: '{{ route('sessionFormations.edit',  ['sessionFormation' => ':id']) }}',
        indexUrl: '{{ route('sessionFormations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgSessions::sessionFormation.singular") }} - {{ $itemSessionFormation }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemSessionFormation }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-sessionFormation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sessionFormation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-map"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sessionFormation-hasmany-tabs-home-tab" data-toggle="pill" href="#sessionFormation-hasmany-tabs-home" role="tab" aria-controls="sessionFormation-hasmany-tabs-home" aria-selected="true">{{__('PkgSessions::sessionFormation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sessionFormation-hasmany-tabs-alignementUa-tab" data-toggle="pill" href="#sessionFormation-hasmany-tabs-alignementUa" role="tab" aria-controls="sessionFormation-hasmany-tabs-alignementUa" aria-selected="false">
                                <i class="nav-icon fas fa-road"></i>
                                {{ucfirst(__('PkgSessions::alignementUa.plural'))}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sessionFormation-hasmany-tabs-livrableSession-tab" data-toggle="pill" href="#sessionFormation-hasmany-tabs-livrableSession" role="tab" aria-controls="sessionFormation-hasmany-tabs-livrableSession" aria-selected="false">
                                <i class="nav-icon fas fa-folder"></i>
                                {{ucfirst(__('PkgSessions::livrableSession.plural'))}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sessionFormation-hasmany-tabs-projet-tab" data-toggle="pill" href="#sessionFormation-hasmany-tabs-projet" role="tab" aria-controls="sessionFormation-hasmany-tabs-projet" aria-selected="false">
                                <i class="nav-icon fas fa-lightbulb"></i>
                                {{ucfirst(__('PkgCreationProjet::projet.plural'))}}
                            </a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sessionFormation-tabContent">
                            <div class="tab-pane fade show active" id="sessionFormation-hasmany-tabs-home" role="tabpanel" aria-labelledby="sessionFormation-hasmany-tabs-home-tab">
                                @include('PkgSessions::sessionFormation._fields')
                            </div>

                            <div class="tab-pane fade" id="sessionFormation-hasmany-tabs-alignementUa" role="tabpanel" aria-labelledby="sessionFormation-hasmany-tabs-alignementUa-tab">
                                @include('PkgSessions::alignementUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.edit_' . $itemSessionFormation->id])
                            </div>
                            <div class="tab-pane fade" id="sessionFormation-hasmany-tabs-livrableSession" role="tabpanel" aria-labelledby="sessionFormation-hasmany-tabs-livrableSession-tab">
                                @include('PkgSessions::livrableSession._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.edit_' . $itemSessionFormation->id])
                            </div>
                            <div class="tab-pane fade" id="sessionFormation-hasmany-tabs-projet" role="tabpanel" aria-labelledby="sessionFormation-hasmany-tabs-projet-tab">
                                @include('PkgCreationProjet::projet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sessionFormation.edit_' . $itemSessionFormation->id])
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
