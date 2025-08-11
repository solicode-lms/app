{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationModule',
        contextKey: 'etatRealisationModule.edit_{{ $itemEtatRealisationModule->id}}',
        cardTabSelector: '#card-tab-etatRealisationModule', 
        formSelector: '#etatRealisationModuleForm',
        editUrl: '{{ route('etatRealisationModules.edit',  ['etatRealisationModule' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationModules.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationModule.singular") }} - {{ $itemEtatRealisationModule }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatRealisationModule }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatRealisationModule" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationModule-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationModule-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationModule-hasmany-tabs-home" role="tab" aria-controls="etatRealisationModule-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::etatRealisationModule.singular')}}</a>
                        </li>

                         @if($itemEtatRealisationModule->realisationModules->count() > 0 || auth()->user()?->can('create-realisationModule'))
                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationModule-hasmany-tabs-realisationModule-tab" data-toggle="pill" href="#etatRealisationModule-hasmany-tabs-realisationModule" role="tab" aria-controls="etatRealisationModule-hasmany-tabs-realisationModule" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgApprentissage::realisationModule.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationModule-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationModule-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationModule-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::etatRealisationModule._fields')
                            </div>

                            @if($itemEtatRealisationModule->realisationModules->count() > 0 || auth()->user()?->can('create-realisationModule'))
                            <div class="tab-pane fade" id="etatRealisationModule-hasmany-tabs-realisationModule" role="tabpanel" aria-labelledby="etatRealisationModule-hasmany-tabs-realisationModule-tab">
                                @include('PkgApprentissage::realisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationModule.edit_' . $itemEtatRealisationModule->id])
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
