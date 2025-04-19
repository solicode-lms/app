{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'formation',
        contextKey: 'formation.edit_{{ $itemFormation->id}}',
        cardTabSelector: '#card-tab-formation', 
        formSelector: '#formationForm',
        editUrl: '{{ route('formations.edit',  ['formation' => ':id']) }}',
        indexUrl: '{{ route('formations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::formation.singular") }} - {{ $itemFormation }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemFormation }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-formation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-formation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="formation-hasmany-tabs-home-tab" data-toggle="pill" href="#formation-hasmany-tabs-home" role="tab" aria-controls="formation-hasmany-tabs-home" aria-selected="true">{{__('PkgAutoformation::formation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="formation-hasmany-tabs-formation-tab" data-toggle="pill" href="#formation-hasmany-tabs-formation" role="tab" aria-controls="formation-hasmany-tabs-formation" aria-selected="false">{{__('PkgAutoformation::formation.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formation-hasmany-tabs-chapitre-tab" data-toggle="pill" href="#formation-hasmany-tabs-chapitre" role="tab" aria-controls="formation-hasmany-tabs-chapitre" aria-selected="false">{{__('PkgAutoformation::chapitre.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="formation-hasmany-tabs-realisationFormation-tab" data-toggle="pill" href="#formation-hasmany-tabs-realisationFormation" role="tab" aria-controls="formation-hasmany-tabs-realisationFormation" aria-selected="false">{{__('PkgAutoformation::realisationFormation.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-formation-tabContent">
                            <div class="tab-pane fade show active" id="formation-hasmany-tabs-home" role="tabpanel" aria-labelledby="formation-hasmany-tabs-home-tab">
                                @include('PkgAutoformation::formation._fields')
                            </div>

                            <div class="tab-pane fade" id="formation-hasmany-tabs-formation" role="tabpanel" aria-labelledby="formation-hasmany-tabs-formation-tab">
                                @include('PkgAutoformation::formation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formation.edit_' . $itemFormation->id])
                            </div>
                            <div class="tab-pane fade" id="formation-hasmany-tabs-chapitre" role="tabpanel" aria-labelledby="formation-hasmany-tabs-chapitre-tab">
                                @include('PkgAutoformation::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formation.edit_' . $itemFormation->id])
                            </div>
                            <div class="tab-pane fade" id="formation-hasmany-tabs-realisationFormation" role="tabpanel" aria-labelledby="formation-hasmany-tabs-realisationFormation-tab">
                                @include('PkgAutoformation::realisationFormation._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'formation.edit_' . $itemFormation->id])
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
