{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'question',
        contextKey: 'question.edit_{{ $itemQuestion->id}}',
        cardTabSelector: '#card-tab-question', 
        formSelector: '#questionForm',
        editUrl: '{{ route('questions.edit',  ['question' => ':id']) }}',
        indexUrl: '{{ route('questions.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgQcm::question.singular") }} - {{ $itemQuestion }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemQuestion }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-question" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-question-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-question"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="question-hasmany-tabs-home-tab" data-toggle="pill" href="#question-hasmany-tabs-home" role="tab" aria-controls="question-hasmany-tabs-home" aria-selected="true">{{__('PkgQcm::question.singular')}}</a>
                        </li>

                         @if($itemQuestion->reponseQcms?->count() > 0 || auth()->user()?->can('create-reponseQcm'))
                        <li class="nav-item">
                            <a class="nav-link" id="question-hasmany-tabs-reponseQcm-tab" data-toggle="pill" href="#question-hasmany-tabs-reponseQcm" role="tab" aria-controls="question-hasmany-tabs-reponseQcm" aria-selected="false">
                                <i class="nav-icon fas fa-check-double"></i>
                                {{ucfirst(__('PkgQcm::reponseQcm.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-question-tabContent">
                            <div class="tab-pane fade show active" id="question-hasmany-tabs-home" role="tabpanel" aria-labelledby="question-hasmany-tabs-home-tab">
                                @include('PkgQcm::question._fields')
                            </div>

                            @if($itemQuestion->reponseQcms?->count() > 0 || auth()->user()?->can('create-reponseQcm'))
                            <div class="tab-pane fade" id="question-hasmany-tabs-reponseQcm" role="tabpanel" aria-labelledby="question-hasmany-tabs-reponseQcm-tab">
                                @include('PkgQcm::reponseQcm._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'question.edit_' . $itemQuestion->id])
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
