{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'groupe',
        contextKey: 'groupe.edit_{{ $itemGroupe->id}}',
        cardTabSelector: '#card-tab-groupe', 
        formSelector: '#groupeForm',
        editUrl: '{{ route('groupes.edit',  ['groupe' => ':id']) }}',
        indexUrl: '{{ route('groupes.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::groupe.singular") }} - {{ $itemGroupe }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemGroupe }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-groupe" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-groupe-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-users"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="groupe-hasmany-tabs-home-tab" data-toggle="pill" href="#groupe-hasmany-tabs-home" role="tab" aria-controls="groupe-hasmany-tabs-home" aria-selected="true">{{__('PkgApprenants::groupe.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="groupe-hasmany-tabs-affectationProjet-tab" data-toggle="pill" href="#groupe-hasmany-tabs-affectationProjet" role="tab" aria-controls="groupe-hasmany-tabs-affectationProjet" aria-selected="false">{{ucfirst(__('PkgRealisationProjets::affectationProjet.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-groupe-tabContent">
                            <div class="tab-pane fade show active" id="groupe-hasmany-tabs-home" role="tabpanel" aria-labelledby="groupe-hasmany-tabs-home-tab">
                                @include('PkgApprenants::groupe._fields')
                            </div>

                            <div class="tab-pane fade" id="groupe-hasmany-tabs-affectationProjet" role="tabpanel" aria-labelledby="groupe-hasmany-tabs-affectationProjet-tab">
                                @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'groupe.edit_' . $itemGroupe->id])
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
