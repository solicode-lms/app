{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'apprenant',
        contextKey: 'apprenant.edit_{{ $itemApprenant->id}}',
        cardTabSelector: '#card-tab-apprenant', 
        formSelector: '#apprenantForm',
        editUrl: '{{ route('apprenants.edit',  ['apprenant' => ':id']) }}',
        indexUrl: '{{ route('apprenants.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::apprenant.singular") }} - {{ $itemApprenant }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemApprenant }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-apprenant" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-apprenant-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-id-card"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="apprenant-hasmany-tabs-home-tab" data-toggle="pill" href="#apprenant-hasmany-tabs-home" role="tab" aria-controls="apprenant-hasmany-tabs-home" aria-selected="true">{{__('PkgApprenants::apprenant.singular')}}</a>
                        </li>

                         @if($itemApprenant->realisationCompetences?->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="apprenant-hasmany-tabs-realisationCompetence-tab" data-toggle="pill" href="#apprenant-hasmany-tabs-realisationCompetence" role="tab" aria-controls="apprenant-hasmany-tabs-realisationCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-award"></i>
                                {{ucfirst(__('PkgApprentissage::realisationCompetence.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemApprenant->realisationMicroCompetences?->count() > 0 || auth()->user()?->can('create-realisationMicroCompetence'))
                        <li class="nav-item">
                            <a class="nav-link" id="apprenant-hasmany-tabs-realisationMicroCompetence-tab" data-toggle="pill" href="#apprenant-hasmany-tabs-realisationMicroCompetence" role="tab" aria-controls="apprenant-hasmany-tabs-realisationMicroCompetence" aria-selected="false">
                                <i class="nav-icon fas fa-certificate"></i>
                                {{ucfirst(__('PkgApprentissage::realisationMicroCompetence.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemApprenant->realisationModules?->count() > 0 || auth()->user()?->can('create-realisationModule'))
                        <li class="nav-item">
                            <a class="nav-link" id="apprenant-hasmany-tabs-realisationModule-tab" data-toggle="pill" href="#apprenant-hasmany-tabs-realisationModule" role="tab" aria-controls="apprenant-hasmany-tabs-realisationModule" aria-selected="false">
                                <i class="nav-icon fas fa-medal"></i>
                                {{ucfirst(__('PkgApprentissage::realisationModule.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-apprenant-tabContent">
                            <div class="tab-pane fade show active" id="apprenant-hasmany-tabs-home" role="tabpanel" aria-labelledby="apprenant-hasmany-tabs-home-tab">
                                @include('PkgApprenants::apprenant._fields')
                            </div>

                            @if($itemApprenant->realisationCompetences?->count() > 0 || auth()->user()?->can('create-realisationCompetence'))
                            <div class="tab-pane fade" id="apprenant-hasmany-tabs-realisationCompetence" role="tabpanel" aria-labelledby="apprenant-hasmany-tabs-realisationCompetence-tab">
                                @include('PkgApprentissage::realisationCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.edit_' . $itemApprenant->id])
                            </div>
                            @endif
                            @if($itemApprenant->realisationMicroCompetences?->count() > 0 || auth()->user()?->can('create-realisationMicroCompetence'))
                            <div class="tab-pane fade" id="apprenant-hasmany-tabs-realisationMicroCompetence" role="tabpanel" aria-labelledby="apprenant-hasmany-tabs-realisationMicroCompetence-tab">
                                @include('PkgApprentissage::realisationMicroCompetence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.edit_' . $itemApprenant->id])
                            </div>
                            @endif
                            @if($itemApprenant->realisationModules?->count() > 0 || auth()->user()?->can('create-realisationModule'))
                            <div class="tab-pane fade" id="apprenant-hasmany-tabs-realisationModule" role="tabpanel" aria-labelledby="apprenant-hasmany-tabs-realisationModule-tab">
                                @include('PkgApprentissage::realisationModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'apprenant.edit_' . $itemApprenant->id])
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
