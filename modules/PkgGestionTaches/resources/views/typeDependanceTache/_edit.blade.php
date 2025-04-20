{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'typeDependanceTache',
        contextKey: 'typeDependanceTache.edit_{{ $itemTypeDependanceTache->id}}',
        cardTabSelector: '#card-tab-typeDependanceTache', 
        formSelector: '#typeDependanceTacheForm',
        editUrl: '{{ route('typeDependanceTaches.edit',  ['typeDependanceTache' => ':id']) }}',
        indexUrl: '{{ route('typeDependanceTaches.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::typeDependanceTache.singular") }} - {{ $itemTypeDependanceTache }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemTypeDependanceTache }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-typeDependanceTache" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-typeDependanceTache-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-random"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="typeDependanceTache-hasmany-tabs-home-tab" data-toggle="pill" href="#typeDependanceTache-hasmany-tabs-home" role="tab" aria-controls="typeDependanceTache-hasmany-tabs-home" aria-selected="true">{{__('PkgGestionTaches::typeDependanceTache.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="typeDependanceTache-hasmany-tabs-dependanceTache-tab" data-toggle="pill" href="#typeDependanceTache-hasmany-tabs-dependanceTache" role="tab" aria-controls="typeDependanceTache-hasmany-tabs-dependanceTache" aria-selected="false">{{ucfirst(__('PkgGestionTaches::dependanceTache.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-typeDependanceTache-tabContent">
                            <div class="tab-pane fade show active" id="typeDependanceTache-hasmany-tabs-home" role="tabpanel" aria-labelledby="typeDependanceTache-hasmany-tabs-home-tab">
                                @include('PkgGestionTaches::typeDependanceTache._fields')
                            </div>

                            <div class="tab-pane fade" id="typeDependanceTache-hasmany-tabs-dependanceTache" role="tabpanel" aria-labelledby="typeDependanceTache-hasmany-tabs-dependanceTache-tab">
                                @include('PkgGestionTaches::dependanceTache._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'typeDependanceTache.edit_' . $itemTypeDependanceTache->id])
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
