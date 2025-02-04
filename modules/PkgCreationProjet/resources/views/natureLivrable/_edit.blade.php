{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'natureLivrable',
        cardTabSelector: '#card-tab-natureLivrable', 
        formSelector: '#natureLivrableForm',
        editUrl: '{{ route('natureLivrables.edit',  ['natureLivrable' => ':id']) }}',
        indexUrl: '{{ route('natureLivrables.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
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
                <div id="card-tab-natureLivrable" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-natureLivrable-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-file-archive"></i>
                                {{ __('Core::msg.edit') }}
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="natureLivrable-hasmany-tabs-home-tab" data-toggle="pill" href="#natureLivrable-hasmany-tabs-home" role="tab" aria-controls="natureLivrable-hasmany-tabs-home" aria-selected="true">{{__('PkgCreationProjet::natureLivrable.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="natureLivrable-hasmany-tabs-livrable-tab" data-toggle="pill" href="#natureLivrable-hasmany-tabs-livrable" role="tab" aria-controls="natureLivrable-hasmany-tabs-livrable" aria-selected="false">{{__('PkgCreationProjet::livrable.plural')}}</a>
                        </li>

                       
                        </ul>
                         <button type="button" class="btn btn-info btn-sm btn-card-header">
                            <i class="fa fa-check"></i>
                                Enregistrer
                         </button>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-natureLivrable-tabContent">
                            <div class="tab-pane fade show active" id="natureLivrable-hasmany-tabs-home" role="tabpanel" aria-labelledby="natureLivrable-hasmany-tabs-home-tab">
                                @include('PkgCreationProjet::natureLivrable._fields')
                            </div>

                            <div class="tab-pane fade" id="natureLivrable-hasmany-tabs-livrable" role="tabpanel" aria-labelledby="natureLivrable-hasmany-tabs-livrable-tab">
                                @include('PkgCreationProjet::livrable._index',['isMany' => true, "edit_has_many" => false])
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
