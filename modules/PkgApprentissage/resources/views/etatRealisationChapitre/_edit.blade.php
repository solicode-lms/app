{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationChapitre',
        contextKey: 'etatRealisationChapitre.edit_{{ $itemEtatRealisationChapitre->id}}',
        cardTabSelector: '#card-tab-etatRealisationChapitre', 
        formSelector: '#etatRealisationChapitreForm',
        editUrl: '{{ route('etatRealisationChapitres.edit',  ['etatRealisationChapitre' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationChapitres.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationChapitre.singular") }} - {{ $itemEtatRealisationChapitre }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatRealisationChapitre }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatRealisationChapitre" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationChapitre-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationChapitre-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationChapitre-hasmany-tabs-home" role="tab" aria-controls="etatRealisationChapitre-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::etatRealisationChapitre.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationChapitre-hasmany-tabs-realisationChapitre-tab" data-toggle="pill" href="#etatRealisationChapitre-hasmany-tabs-realisationChapitre" role="tab" aria-controls="etatRealisationChapitre-hasmany-tabs-realisationChapitre" aria-selected="false">{{ucfirst(__('PkgApprentissage::realisationChapitre.plural'))}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationChapitre-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationChapitre-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationChapitre-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::etatRealisationChapitre._fields')
                            </div>

                            <div class="tab-pane fade" id="etatRealisationChapitre-hasmany-tabs-realisationChapitre" role="tabpanel" aria-labelledby="etatRealisationChapitre-hasmany-tabs-realisationChapitre-tab">
                                @include('PkgApprentissage::realisationChapitre._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationChapitre.edit_' . $itemEtatRealisationChapitre->id])
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
