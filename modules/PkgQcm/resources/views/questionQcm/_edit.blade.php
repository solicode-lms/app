{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'questionQcm',
        contextKey: 'questionQcm.edit_{{ $itemQuestionQcm->id}}',
        cardTabSelector: '#card-tab-questionQcm', 
        formSelector: '#questionQcmForm',
        editUrl: '{{ route('questionQcms.edit',  ['questionQcm' => ':id']) }}',
        indexUrl: '{{ route('questionQcms.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::questionQcm.singular") }} - {{ $itemQuestionQcm }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemQuestionQcm }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-questionQcm" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-questionQcm-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-chalkboard"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="questionQcm-hasmany-tabs-home-tab" data-toggle="pill" href="#questionQcm-hasmany-tabs-home" role="tab" aria-controls="questionQcm-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::questionQcm.singular')}}</a>
                        </li>

                         @if($itemQuestionQcm->reponseQcms?->count() > 0 || auth()->user()?->can('create-reponseQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="questionQcm-hasmany-tabs-reponseQcm-tab" data-toggle="pill" href="#questionQcm-hasmany-tabs-reponseQcm" role="tab" aria-controls="questionQcm-hasmany-tabs-reponseQcm" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgQcm::reponseQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-questionQcm-tabContent">
                            <div class="tab-pane fade show active" id="questionQcm-hasmany-tabs-home" role="tabpanel" aria-labelledby="questionQcm-hasmany-tabs-home-tab">
                                @include('PkgQcm::questionQcm._fields')
                            </div>

                            @if($itemQuestionQcm->reponseQcms?->count() > 0 || auth()->user()?->can('create-reponseQcm'))
                            <div class="tab-pane fade" id="questionQcm-hasmany-tabs-reponseQcm" role="tabpanel" aria-labelledby="questionQcm-hasmany-tabs-reponseQcm-tab">
                                @include('PkgQcm::reponseQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'questionQcm.edit_' . $itemQuestionQcm->id])
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
