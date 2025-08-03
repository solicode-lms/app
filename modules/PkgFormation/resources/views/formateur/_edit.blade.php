{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'formateur',
        contextKey: 'formateur.edit_{{ $itemFormateur->id}}',
        cardTabSelector: '#card-tab-formateur', 
        formSelector: '#formateurForm',
        editUrl: '{{ route('formateurs.edit',  ['formateur' => ':id']) }}',
        indexUrl: '{{ route('formateurs.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::formateur.singular") }} - {{ $itemFormateur }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemFormateur }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-formateur" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-formateur-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-user-tie"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="formateur-hasmany-tabs-home-tab" data-toggle="pill" href="#formateur-hasmany-tabs-home" role="tab" aria-controls="formateur-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::formateur.singular')}}</a>
                        </li>

                         @if($itemFormateur->chapitres->count() > 0 || auth()->user()?->can('create-chapitre'))
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-chapitre-tab" data-toggle="pill" href="#formateur-hasmany-tabs-chapitre" role="tab" aria-controls="formateur-hasmany-tabs-chapitre" aria-selected="false">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                {{ucfirst(__('PkgCompetences::chapitre.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemFormateur->etatRealisationTaches->count() > 0 || auth()->user()?->can('create-etatRealisationTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-etatRealisationTache-tab" data-toggle="pill" href="#formateur-hasmany-tabs-etatRealisationTache" role="tab" aria-controls="formateur-hasmany-tabs-etatRealisationTache" aria-selected="false">
                                <i class="nav-icon fas fa-check"></i>
                                {{ucfirst(__('PkgRealisationTache::etatRealisationTache.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemFormateur->prioriteTaches->count() > 0 || auth()->user()?->can('create-prioriteTache'))
                        <li class="nav-item">
                            <a class="nav-link" id="formateur-hasmany-tabs-prioriteTache-tab" data-toggle="pill" href="#formateur-hasmany-tabs-prioriteTache" role="tab" aria-controls="formateur-hasmany-tabs-prioriteTache" aria-selected="false">
                                <i class="nav-icon fas fa-list-ol"></i>
                                {{ucfirst(__('PkgCreationTache::prioriteTache.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-formateur-tabContent">
                            <div class="tab-pane fade show active" id="formateur-hasmany-tabs-home" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-home-tab">
                                @include('PkgFormation::formateur._fields')
                            </div>

                            @if($itemFormateur->chapitres->count() > 0 || auth()->user()?->can('create-chapitre'))
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-chapitre" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-chapitre-tab">
                                @include('PkgCompetences::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            @endif
                            @if($itemFormateur->etatRealisationTaches->count() > 0 || auth()->user()?->can('create-etatRealisationTache'))
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-etatRealisationTache" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-etatRealisationTache-tab">
                                @include('PkgRealisationTache::etatRealisationTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
                            </div>
                            @endif
                            @if($itemFormateur->prioriteTaches->count() > 0 || auth()->user()?->can('create-prioriteTache'))
                            <div class="tab-pane fade" id="formateur-hasmany-tabs-prioriteTache" role="tabpanel" aria-labelledby="formateur-hasmany-tabs-prioriteTache-tab">
                                @include('PkgCreationTache::prioriteTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formateur.edit_' . $itemFormateur->id])
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
