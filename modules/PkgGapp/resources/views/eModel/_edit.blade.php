{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'eModel',
        contextKey: 'eModel.edit_{{ $itemEModel->id}}',
        cardTabSelector: '#card-tab-eModel', 
        formSelector: '#eModelForm',
        editUrl: '{{ route('eModels.edit',  ['eModel' => ':id']) }}',
        indexUrl: '{{ route('eModels.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eModel.singular") }} - {{ $itemEModel }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEModel }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-eModel" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eModel-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eModel-hasmany-tabs-home-tab" data-toggle="pill" href="#eModel-hasmany-tabs-home" role="tab" aria-controls="eModel-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eModel.singular')}}</a>
                        </li>

                         @if($itemEModel->eDataFields->count() > 0 || auth()->user()?->can('create-eDataField'))
                        <li class="nav-item">
                            <a class="nav-link" id="eModel-hasmany-tabs-eDataField-tab" data-toggle="pill" href="#eModel-hasmany-tabs-eDataField" role="tab" aria-controls="eModel-hasmany-tabs-eDataField" aria-selected="false">
                                <i class="nav-icon fas fa-th"></i>
                                {{ucfirst(__('PkgGapp::eDataField.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemEModel->eMetadata->count() > 0 || auth()->user()?->can('create-eMetadatum'))
                        <li class="nav-item">
                            <a class="nav-link" id="eModel-hasmany-tabs-eMetadatum-tab" data-toggle="pill" href="#eModel-hasmany-tabs-eMetadatum" role="tab" aria-controls="eModel-hasmany-tabs-eMetadatum" aria-selected="false">
                                <i class="nav-icon fas fa-th-list"></i>
                                {{ucfirst(__('PkgGapp::eMetadatum.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eModel-tabContent">
                            <div class="tab-pane fade show active" id="eModel-hasmany-tabs-home" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-home-tab">
                                @include('PkgGapp::eModel._fields')
                            </div>

                            @if($itemEModel->eDataFields->count() > 0 || auth()->user()?->can('create-eDataField'))
                            <div class="tab-pane fade" id="eModel-hasmany-tabs-eDataField" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-eDataField-tab">
                                @include('PkgGapp::eDataField._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eModel.edit_' . $itemEModel->id])
                            </div>
                            @endif
                            @if($itemEModel->eMetadata->count() > 0 || auth()->user()?->can('create-eMetadatum'))
                            <div class="tab-pane fade" id="eModel-hasmany-tabs-eMetadatum" role="tabpanel" aria-labelledby="eModel-hasmany-tabs-eMetadatum-tab">
                                @include('PkgGapp::eMetadatum._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eModel.edit_' . $itemEModel->id])
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
