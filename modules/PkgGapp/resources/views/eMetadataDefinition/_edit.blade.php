{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'eMetadataDefinition',
        contextKey: 'eMetadataDefinition.edit_{{ $itemEMetadataDefinition->id}}',
        cardTabSelector: '#card-tab-eMetadataDefinition', 
        formSelector: '#eMetadataDefinitionForm',
        editUrl: '{{ route('eMetadataDefinitions.edit',  ['eMetadataDefinition' => ':id']) }}',
        indexUrl: '{{ route('eMetadataDefinitions.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eMetadataDefinition.singular") }} - {{ $itemEMetadataDefinition }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEMetadataDefinition }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-eMetadataDefinition" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eMetadataDefinition-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-database"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eMetadataDefinition-hasmany-tabs-home-tab" data-toggle="pill" href="#eMetadataDefinition-hasmany-tabs-home" role="tab" aria-controls="eMetadataDefinition-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eMetadataDefinition.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="eMetadataDefinition-hasmany-tabs-eMetadatum-tab" data-toggle="pill" href="#eMetadataDefinition-hasmany-tabs-eMetadatum" role="tab" aria-controls="eMetadataDefinition-hasmany-tabs-eMetadatum" aria-selected="false">{{__('PkgGapp::eMetadatum.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eMetadataDefinition-tabContent">
                            <div class="tab-pane fade show active" id="eMetadataDefinition-hasmany-tabs-home" role="tabpanel" aria-labelledby="eMetadataDefinition-hasmany-tabs-home-tab">
                                @include('PkgGapp::eMetadataDefinition._fields')
                            </div>

                            <div class="tab-pane fade" id="eMetadataDefinition-hasmany-tabs-eMetadatum" role="tabpanel" aria-labelledby="eMetadataDefinition-hasmany-tabs-eMetadatum-tab">
                                @include('PkgGapp::eMetadatum._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eMetadataDefinition.edit_' . $itemEMetadataDefinition->id])
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
