{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'filiere',
        contextKey: 'filiere.edit_' . $itemFiliere->id,
        cardTabSelector: '#card-tab-filiere', 
        formSelector: '#filiereForm',
        editUrl: '{{ route('filieres.edit',  ['filiere' => ':id']) }}',
        indexUrl: '{{ route('filieres.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::filiere.singular") }}',
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
                <div id="card-tab-filiere" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-filiere-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-book"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="filiere-hasmany-tabs-home-tab" data-toggle="pill" href="#filiere-hasmany-tabs-home" role="tab" aria-controls="filiere-hasmany-tabs-home" aria-selected="true">{{__('PkgFormation::filiere.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="filiere-hasmany-tabs-groupe-tab" data-toggle="pill" href="#filiere-hasmany-tabs-groupe" role="tab" aria-controls="filiere-hasmany-tabs-groupe" aria-selected="false">{{__('PkgApprenants::groupe.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="filiere-hasmany-tabs-module-tab" data-toggle="pill" href="#filiere-hasmany-tabs-module" role="tab" aria-controls="filiere-hasmany-tabs-module" aria-selected="false">{{__('PkgFormation::module.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-filiere-tabContent">
                            <div class="tab-pane fade show active" id="filiere-hasmany-tabs-home" role="tabpanel" aria-labelledby="filiere-hasmany-tabs-home-tab">
                                @include('PkgFormation::filiere._fields')
                            </div>

                            <div class="tab-pane fade" id="filiere-hasmany-tabs-groupe" role="tabpanel" aria-labelledby="filiere-hasmany-tabs-groupe-tab">
                                @include('PkgApprenants::groupe._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'filiere.edit_' . $itemFiliere->id])
                            </div>
                            <div class="tab-pane fade" id="filiere-hasmany-tabs-module" role="tabpanel" aria-labelledby="filiere-hasmany-tabs-module-tab">
                                @include('PkgFormation::module._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'filiere.edit_' . $itemFiliere->id])
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
