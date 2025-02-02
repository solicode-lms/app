{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'niveauDifficulte',
        cardTabSelector: '#card-tab-niveauDifficulte', 
        formSelector: '#niveauDifficulteForm',
        editUrl: '{{ route('niveauDifficultes.edit',  ['niveauDifficulte' => ':id']) }}',
        indexUrl: '{{ route('niveauDifficultes.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::niveauDifficulte.singular") }}',
    });
</script>
<script>
    window.contextState = @json($contextState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-niveauDifficulte" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-niveauDifficulte-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="niveauDifficulte-hasmany-tabs-home-tab" data-toggle="pill" href="#niveauDifficulte-hasmany-tabs-home" role="tab" aria-controls="niveauDifficulte-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::niveauDifficulte.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="niveauDifficulte-hasmany-tabs-transfertCompetence-tab" data-toggle="pill" href="#niveauDifficulte-hasmany-tabs-transfertCompetence" role="tab" aria-controls="niveauDifficulte-hasmany-tabs-transfertCompetence" aria-selected="false">{{__('PkgCreationProjet::transfertCompetence.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-niveauDifficulte-tabContent">
                            <div class="tab-pane fade show active" id="niveauDifficulte-hasmany-tabs-home" role="tabpanel" aria-labelledby="niveauDifficulte-hasmany-tabs-home-tab">
                                @include('PkgCompetences::niveauDifficulte._fields')
                            </div>

                            <div class="tab-pane fade" id="niveauDifficulte-hasmany-tabs-transfertCompetence" role="tabpanel" aria-labelledby="niveauDifficulte-hasmany-tabs-transfertCompetence-tab">
                                @include('PkgCreationProjet::transfertCompetence._index',['isMany' => true, "edit_has_many" => false])
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
