{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'eDataField',
        contextKey: 'eDataField.edit_{{ $itemEDataField->id}}',
        cardTabSelector: '#card-tab-eDataField', 
        formSelector: '#eDataFieldForm',
        editUrl: '{{ route('eDataFields.edit',  ['eDataField' => ':id']) }}',
        indexUrl: '{{ route('eDataFields.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: 'aaa',
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
                <div id="card-tab-eDataField" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eDataField-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-th"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eDataField-hasmany-tabs-home-tab" data-toggle="pill" href="#eDataField-hasmany-tabs-home" role="tab" aria-controls="eDataField-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eDataField.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="eDataField-hasmany-tabs-eMetadatum-tab" data-toggle="pill" href="#eDataField-hasmany-tabs-eMetadatum" role="tab" aria-controls="eDataField-hasmany-tabs-eMetadatum" aria-selected="false">{{__('PkgGapp::eMetadatum.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eDataField-tabContent">
                            <div class="tab-pane fade show active" id="eDataField-hasmany-tabs-home" role="tabpanel" aria-labelledby="eDataField-hasmany-tabs-home-tab">
                                @include('PkgGapp::eDataField._fields')
                            </div>

                            <div class="tab-pane fade" id="eDataField-hasmany-tabs-eMetadatum" role="tabpanel" aria-labelledby="eDataField-hasmany-tabs-eMetadatum-tab">
                                @include('PkgGapp::eMetadatum._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eDataField.edit_' . $itemEDataField->id])
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
