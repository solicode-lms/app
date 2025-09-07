{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'realisationModule',
        contextKey: 'realisationModule.edit_{{ $itemRealisationModule->id}}',
        cardTabSelector: '#card-tab-realisationModule', 
        formSelector: '#realisationModuleForm',
        editUrl: '{{ route('realisationModules.edit',  ['realisationModule' => ':id']) }}',
        indexUrl: '{{ route('realisationModules.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationModule.singular") }} - {{ $itemRealisationModule }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemRealisationModule }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-realisationModule" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-realisationModule-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-medal"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="realisationModule-hasmany-tabs-home-tab" data-toggle="pill" href="#realisationModule-hasmany-tabs-home" role="tab" aria-controls="realisationModule-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::realisationModule.singular')}}</a>
                        </li>

                         @if($itemRealisationModule->realisationCompetences?->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="realisationModule-hasmany-tabs-realisationCompetence-tab" data-toggle="pill" href="#realisationModule-hasmany-tabs-realisationCompetence" role="tab" aria-controls="realisationModule-hasmany-tabs-realisationCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-award"></i>
                                {{ucfirst(__('PkgApprentissage::realisationCompetence.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-realisationModule-tabContent">
                            <div class="tab-pane fade show active" id="realisationModule-hasmany-tabs-home" role="tabpanel" aria-labelledby="realisationModule-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::realisationModule._fields')
                            </div>

                            @if($itemRealisationModule->realisationCompetences?->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                            <div class="tab-pane fade" id="realisationModule-hasmany-tabs-realisationCompetence" role="tabpanel" aria-labelledby="realisationModule-hasmany-tabs-realisationCompetence-tab">
                                @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'realisationModule.edit_' . $itemRealisationModule->id])
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
