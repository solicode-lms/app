{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'mobilisationUa',
        contextKey: 'mobilisationUa.edit_{{ $itemMobilisationUa->id}}',
        cardTabSelector: '#card-tab-mobilisationUa', 
        formSelector: '#mobilisationUaForm',
        editUrl: '{{ route('mobilisationUas.edit',  ['mobilisationUa' => ':id']) }}',
        indexUrl: '{{ route('mobilisationUas.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::mobilisationUa.singular") }} - {{ $itemMobilisationUa }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemMobilisationUa }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-mobilisationUa" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-mobilisationUa-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas  fa-seedling"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="mobilisationUa-hasmany-tabs-home-tab" data-toggle="pill" href="#mobilisationUa-hasmany-tabs-home" role="tab" aria-controls="mobilisationUa-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationProjet::mobilisationUa.singular')}}</a>
                        </li>

                         @if($itemMobilisationUa->taches?->count() > 0 || auth()->user()?->can('create-tache'))
                        <li class="nav-item">
                            <a class="nav-link" id="mobilisationUa-hasmany-tabs-tache-tab" data-toggle="pill" href="#mobilisationUa-hasmany-tabs-tache" role="tab" aria-controls="mobilisationUa-hasmany-tabs-tache" aria-selected="false">
                                <i class="nav-icon fas fa-tasks"></i>
                                {{ucfirst(__('PkgCreationTache::tache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-mobilisationUa-tabContent">
                            <div class="tab-pane fade show active" id="mobilisationUa-hasmany-tabs-home" role="tabpanel" aria-labelledby="mobilisationUa-hasmany-tabs-home-tab">
                                @include('PkgCreationProjet::mobilisationUa._fields')
                            </div>

                            @if($itemMobilisationUa->taches?->count() > 0 || auth()->user()?->can('create-tache'))
                            <div class="tab-pane fade" id="mobilisationUa-hasmany-tabs-tache" role="tabpanel" aria-labelledby="mobilisationUa-hasmany-tabs-tache-tab">
                                @include('PkgCreationTache::tache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'mobilisationUa.edit_' . $itemMobilisationUa->id])
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
