{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'eRelationship',
        contextKey: 'eRelationship.edit_{{ $itemERelationship->id}}',
        cardTabSelector: '#card-tab-eRelationship', 
        formSelector: '#eRelationshipForm',
        editUrl: '{{ route('eRelationships.edit',  ['eRelationship' => ':id']) }}',
        indexUrl: '{{ route('eRelationships.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eRelationship.singular") }} - {{ $itemERelationship }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemERelationship }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-eRelationship" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-eRelationship-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-directions"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="eRelationship-hasmany-tabs-home-tab" data-toggle="pill" href="#eRelationship-hasmany-tabs-home" role="tab" aria-controls="eRelationship-hasmany-tabs-home" aria-selected="true">{{__('PkgGapp::eRelationship.singular')}}</a>
                        </li>

                         @if($itemRealisationTache->eDataFields->count() > 0 || auth()->user()?->can('create-eDataField'))
                        <li class="nav-item">
                            <a class="nav-link" id="eRelationship-hasmany-tabs-eDataField-tab" data-toggle="pill" href="#eRelationship-hasmany-tabs-eDataField" role="tab" aria-controls="eRelationship-hasmany-tabs-eDataField" aria-selected="false">
                                <i class="nav-icon fas fa-th"></i>
                                {{ucfirst(__('PkgGapp::eDataField.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-eRelationship-tabContent">
                            <div class="tab-pane fade show active" id="eRelationship-hasmany-tabs-home" role="tabpanel" aria-labelledby="eRelationship-hasmany-tabs-home-tab">
                                @include('PkgGapp::eRelationship._fields')
                            </div>

                            @if($itemRealisationTache->eDataFields->count() > 0 || auth()->user()?->can('create-eDataField'))
                            <div class="tab-pane fade" id="eRelationship-hasmany-tabs-eDataField" role="tabpanel" aria-labelledby="eRelationship-hasmany-tabs-eDataField-tab">
                                @include('PkgGapp::eDataField._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'eRelationship.edit_' . $itemERelationship->id])
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
