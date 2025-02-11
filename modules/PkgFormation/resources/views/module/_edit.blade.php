{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'module',
        contextKey: 'module.edit_{{$itemProjet->id}}',
        cardTabSelector: '#card-tab-module', 
        formSelector: '#moduleForm',
        editUrl: '{{ route('modules.edit',  ['module' => ':id']) }}',
        indexUrl: '{{ route('modules.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::module.singular") }}',
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
                <div id="card-tab-module" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-module-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-puzzle-piece"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="module-hasmany-tabs-home-tab" data-toggle="pill" href="#module-hasmany-tabs-home" role="tab" aria-controls="module-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::module.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="module-hasmany-tabs-competence-tab" data-toggle="pill" href="#module-hasmany-tabs-competence" role="tab" aria-controls="module-hasmany-tabs-competence" aria-selected="false">{{__('PkgCompetences::competence.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-module-tabContent">
                            <div class="tab-pane fade show active" id="module-hasmany-tabs-home" role="tabpanel" aria-labelledby="module-hasmany-tabs-home-tab">
                                @include('PkgFormation::module._fields')
                            </div>

                            <div class="tab-pane fade" id="module-hasmany-tabs-competence" role="tabpanel" aria-labelledby="module-hasmany-tabs-competence-tab">
                                @include('PkgCompetences::competence._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'module.edit_' . $itemModule->id])
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
