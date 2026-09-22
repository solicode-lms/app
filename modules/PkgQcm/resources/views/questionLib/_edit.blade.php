{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'questionLib',
        contextKey: 'questionLib.edit_{{ $itemQuestionLib->id}}',
        cardTabSelector: '#card-tab-questionLib', 
        formSelector: '#questionLibForm',
        editUrl: '{{ route('questionLibs.edit',  ['questionLib' => ':id']) }}',
        indexUrl: '{{ route('questionLibs.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::questionLib.singular") }} - {{ $itemQuestionLib }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemQuestionLib }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-questionLib" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-questionLib-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="questionLib-hasmany-tabs-home-tab" data-toggle="pill" href="#questionLib-hasmany-tabs-home" role="tab" aria-controls="questionLib-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::questionLib.singular')}}</a>
                        </li>

                         @if($itemQuestionLib->propositionReponses?->count() > 0 || auth()->user()?->can('create-propositionReponse'))
                        <li class="nav-item">
                            <a class="nav-link" id="questionLib-hasmany-tabs-propositionReponse-tab" data-toggle="pill" href="#questionLib-hasmany-tabs-propositionReponse" role="tab" aria-controls="questionLib-hasmany-tabs-propositionReponse" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgQcm::propositionReponse.plural'))}}
                            </a>
                        </li>
                        @endif
                         @if($itemQuestionLib->questionQcms?->count() > 0 || auth()->user()?->can('create-questionQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="questionLib-hasmany-tabs-questionQcm-tab" data-toggle="pill" href="#questionLib-hasmany-tabs-questionQcm" role="tab" aria-controls="questionLib-hasmany-tabs-questionQcm" aria-selected="false">
                                <i class="nav-icon fas fa-table"></i>
                                {{ucfirst(__('PkgQcm::questionQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-questionLib-tabContent">
                            <div class="tab-pane fade show active" id="questionLib-hasmany-tabs-home" role="tabpanel" aria-labelledby="questionLib-hasmany-tabs-home-tab">
                                @include('PkgQcm::questionLib._fields')
                            </div>

                            @if($itemQuestionLib->propositionReponses?->count() > 0 || auth()->user()?->can('create-propositionReponse'))
                            <div class="tab-pane fade" id="questionLib-hasmany-tabs-propositionReponse" role="tabpanel" aria-labelledby="questionLib-hasmany-tabs-propositionReponse-tab">
                                @include('PkgQcm::propositionReponse._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'questionLib.edit_' . $itemQuestionLib->id])
                            </div>
                            @endif
                            @if($itemQuestionLib->questionQcms?->count() > 0 || auth()->user()?->can('create-questionQcm'))
                            <div class="tab-pane fade" id="questionLib-hasmany-tabs-questionQcm" role="tabpanel" aria-labelledby="questionLib-hasmany-tabs-questionQcm-tab">
                                @include('PkgQcm::questionQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'questionLib.edit_' . $itemQuestionLib->id])
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
