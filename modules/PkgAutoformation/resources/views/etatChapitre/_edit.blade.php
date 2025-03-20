{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatChapitre',
        contextKey: 'etatChapitre.edit_{{ $itemEtatChapitre->id}}',
        cardTabSelector: '#card-tab-etatChapitre', 
        formSelector: '#etatChapitreForm',
        editUrl: '{{ route('etatChapitres.edit',  ['etatChapitre' => ':id']) }}',
        indexUrl: '{{ route('etatChapitres.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::etatChapitre.singular") }}',
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
                <div id="card-tab-etatChapitre" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatChapitre-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatChapitre-hasmany-tabs-home-tab" data-toggle="pill" href="#etatChapitre-hasmany-tabs-home" role="tab" aria-controls="etatChapitre-hasmany-tabs-home" aria-selected="true">{{__('PkgAutoformation::etatChapitre.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatChapitre-hasmany-tabs-realisationChapitre-tab" data-toggle="pill" href="#etatChapitre-hasmany-tabs-realisationChapitre" role="tab" aria-controls="etatChapitre-hasmany-tabs-realisationChapitre" aria-selected="false">{{__('PkgAutoformation::realisationChapitre.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatChapitre-tabContent">
                            <div class="tab-pane fade show active" id="etatChapitre-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatChapitre-hasmany-tabs-home-tab">
                                @include('PkgAutoformation::etatChapitre._fields')
                            </div>

                            <div class="tab-pane fade" id="etatChapitre-hasmany-tabs-realisationChapitre" role="tabpanel" aria-labelledby="etatChapitre-hasmany-tabs-realisationChapitre-tab">
                                @include('PkgAutoformation::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatChapitre.edit_' . $itemEtatChapitre->id])
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
