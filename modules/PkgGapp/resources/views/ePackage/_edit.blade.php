{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'ePackage',
        contextKey: 'ePackage.edit_{{ $itemEPackage->id}}',
        cardTabSelector: '#card-tab-ePackage', 
        formSelector: '#ePackageForm',
        editUrl: '{{ route('ePackages.edit',  ['ePackage' => ':id']) }}',
        indexUrl: '{{ route('ePackages.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::ePackage.singular") }} - {{ $itemEPackage }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEPackage }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-ePackage" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-ePackage-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-box"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="ePackage-hasmany-tabs-home-tab" data-toggle="pill" href="#ePackage-hasmany-tabs-home" role="tab" aria-controls="ePackage-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::ePackage.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="ePackage-hasmany-tabs-eModel-tab" data-toggle="pill" href="#ePackage-hasmany-tabs-eModel" role="tab" aria-controls="ePackage-hasmany-tabs-eModel" aria-selected="false">{{ucfirst(__('PkgGapp::eModel.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-ePackage-tabContent">
                            <div class="tab-pane fade show active" id="ePackage-hasmany-tabs-home" role="tabpanel" aria-labelledby="ePackage-hasmany-tabs-home-tab">
                                @include('PkgGapp::ePackage._fields')
                            </div>

                            <div class="tab-pane fade" id="ePackage-hasmany-tabs-eModel" role="tabpanel" aria-labelledby="ePackage-hasmany-tabs-eModel-tab">
                                @include('PkgGapp::eModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'ePackage.edit_' . $itemEPackage->id])
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
