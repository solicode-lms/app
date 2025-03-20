{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'niveauCompetence',
        contextKey: 'niveauCompetence.edit_{{ $itemNiveauCompetence->id}}',
        cardTabSelector: '#card-tab-niveauCompetence', 
        formSelector: '#niveauCompetenceForm',
        editUrl: '{{ route('niveauCompetences.edit',  ['niveauCompetence' => ':id']) }}',
        indexUrl: '{{ route('niveauCompetences.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
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
                <div id="card-tab-niveauCompetence" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-niveauCompetence-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-bars"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="niveauCompetence-hasmany-tabs-home-tab" data-toggle="pill" href="#niveauCompetence-hasmany-tabs-home" role="tab" aria-controls="niveauCompetence-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::niveauCompetence.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="niveauCompetence-hasmany-tabs-chapitre-tab" data-toggle="pill" href="#niveauCompetence-hasmany-tabs-chapitre" role="tab" aria-controls="niveauCompetence-hasmany-tabs-chapitre" aria-selected="false">{{__('PkgAutoformation::chapitre.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-niveauCompetence-tabContent">
                            <div class="tab-pane fade show active" id="niveauCompetence-hasmany-tabs-home" role="tabpanel" aria-labelledby="niveauCompetence-hasmany-tabs-home-tab">
                                @include('PkgCompetences::niveauCompetence._fields')
                            </div>

                            <div class="tab-pane fade" id="niveauCompetence-hasmany-tabs-chapitre" role="tabpanel" aria-labelledby="niveauCompetence-hasmany-tabs-chapitre-tab">
                                @include('PkgAutoformation::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'niveauCompetence.edit_' . $itemNiveauCompetence->id])
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
