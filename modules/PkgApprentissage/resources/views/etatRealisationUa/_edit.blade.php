{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'etatRealisationUa',
        contextKey: 'etatRealisationUa.edit_{{ $itemEtatRealisationUa->id}}',
        cardTabSelector: '#card-tab-etatRealisationUa', 
        formSelector: '#etatRealisationUaForm',
        editUrl: '{{ route('etatRealisationUas.edit',  ['etatRealisationUa' => ':id']) }}',
        indexUrl: '{{ route('etatRealisationUas.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationUa.singular") }} - {{ $itemEtatRealisationUa }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemEtatRealisationUa }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-etatRealisationUa" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-etatRealisationUa-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-check-square"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="etatRealisationUa-hasmany-tabs-home-tab" data-toggle="pill" href="#etatRealisationUa-hasmany-tabs-home" role="tab" aria-controls="etatRealisationUa-hasmany-tabs-home" aria-selected="true">{{__('PkgApprentissage::etatRealisationUa.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="etatRealisationUa-hasmany-tabs-realisationUa-tab" data-toggle="pill" href="#etatRealisationUa-hasmany-tabs-realisationUa" role="tab" aria-controls="etatRealisationUa-hasmany-tabs-realisationUa" aria-selected="false">
                                <i class="nav-icon fas fa-tools"></i>
                                {{ucfirst(__('PkgApprentissage::realisationUa.plural'))}}
                            </a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-etatRealisationUa-tabContent">
                            <div class="tab-pane fade show active" id="etatRealisationUa-hasmany-tabs-home" role="tabpanel" aria-labelledby="etatRealisationUa-hasmany-tabs-home-tab">
                                @include('PkgApprentissage::etatRealisationUa._fields')
                            </div>

                            <div class="tab-pane fade" id="etatRealisationUa-hasmany-tabs-realisationUa" role="tabpanel" aria-labelledby="etatRealisationUa-hasmany-tabs-realisationUa-tab">
                                @include('PkgApprentissage::realisationUa._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'etatRealisationUa.edit_' . $itemEtatRealisationUa->id])
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
