{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'chapitre',
        contextKey: 'chapitre.edit_{{ $itemChapitre->id}}',
        cardTabSelector: '#card-tab-chapitre', 
        formSelector: '#chapitreForm',
        editUrl: '{{ route('chapitres.edit',  ['chapitre' => ':id']) }}',
        indexUrl: '{{ route('chapitres.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::chapitre.singular") }} - {{ $itemChapitre }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemChapitre }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-chapitre" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-chapitre-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-chalkboard"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="chapitre-hasmany-tabs-home-tab" data-toggle="pill" href="#chapitre-hasmany-tabs-home" role="tab" aria-controls="chapitre-hasmany-tabs-home" aria-selected="true">{{__('PkgAutoformation::chapitre.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="chapitre-hasmany-tabs-chapitre-tab" data-toggle="pill" href="#chapitre-hasmany-tabs-chapitre" role="tab" aria-controls="chapitre-hasmany-tabs-chapitre" aria-selected="false">{{__('PkgAutoformation::chapitre.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="chapitre-hasmany-tabs-realisationChapitre-tab" data-toggle="pill" href="#chapitre-hasmany-tabs-realisationChapitre" role="tab" aria-controls="chapitre-hasmany-tabs-realisationChapitre" aria-selected="false">{{__('PkgAutoformation::realisationChapitre.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-chapitre-tabContent">
                            <div class="tab-pane fade show active" id="chapitre-hasmany-tabs-home" role="tabpanel" aria-labelledby="chapitre-hasmany-tabs-home-tab">
                                @include('PkgAutoformation::chapitre._fields')
                            </div>

                            <div class="tab-pane fade" id="chapitre-hasmany-tabs-chapitre" role="tabpanel" aria-labelledby="chapitre-hasmany-tabs-chapitre-tab">
                                @include('PkgAutoformation::chapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'chapitre.edit_' . $itemChapitre->id])
                            </div>
                            <div class="tab-pane fade" id="chapitre-hasmany-tabs-realisationChapitre" role="tabpanel" aria-labelledby="chapitre-hasmany-tabs-realisationChapitre-tab">
                                @include('PkgAutoformation::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'chapitre.edit_' . $itemChapitre->id])
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
