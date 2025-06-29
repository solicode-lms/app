{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sousGroupe',
        contextKey: 'sousGroupe.edit_{{ $itemSousGroupe->id}}',
        cardTabSelector: '#card-tab-sousGroupe', 
        formSelector: '#sousGroupeForm',
        editUrl: '{{ route('sousGroupes.edit',  ['sousGroupe' => ':id']) }}',
        indexUrl: '{{ route('sousGroupes.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::sousGroupe.singular") }} - {{ $itemSousGroupe }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemSousGroupe }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-sousGroupe" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sousGroupe-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-user-friends"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sousGroupe-hasmany-tabs-home-tab" data-toggle="pill" href="#sousGroupe-hasmany-tabs-home" role="tab" aria-controls="sousGroupe-hasmany-tabs-home" aria-selected="true">{{__('PkgApprenants::sousGroupe.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sousGroupe-hasmany-tabs-affectationProjet-tab" data-toggle="pill" href="#sousGroupe-hasmany-tabs-affectationProjet" role="tab" aria-controls="sousGroupe-hasmany-tabs-affectationProjet" aria-selected="false">{{ucfirst(__('PkgRealisationProjets::affectationProjet.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sousGroupe-tabContent">
                            <div class="tab-pane fade show active" id="sousGroupe-hasmany-tabs-home" role="tabpanel" aria-labelledby="sousGroupe-hasmany-tabs-home-tab">
                                @include('PkgApprenants::sousGroupe._fields')
                            </div>

                            <div class="tab-pane fade" id="sousGroupe-hasmany-tabs-affectationProjet" role="tabpanel" aria-labelledby="sousGroupe-hasmany-tabs-affectationProjet-tab">
                                @include('PkgRealisationProjets::affectationProjet._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sousGroupe.edit_' . $itemSousGroupe->id])
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
