{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'widgetType',
        contextKey: 'widgetType.edit_{{ $itemWidgetType->id}}',
        cardTabSelector: '#card-tab-widgetType', 
        formSelector: '#widgetTypeForm',
        editUrl: '{{ route('widgetTypes.edit',  ['widgetType' => ':id']) }}',
        indexUrl: '{{ route('widgetTypes.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widgetType.singular") }} - {{ $itemWidgetType }}',
    });
</script>
<script>
    window.modalTitle = '{{ $itemWidgetType }}';
    window.contextState = @json($contextState);
    window.viewState = @json($viewState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-widgetType" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-widgetType-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-cube"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="widgetType-hasmany-tabs-home-tab" data-toggle="pill" href="#widgetType-hasmany-tabs-home" role="tab" aria-controls="widgetType-hasmany-tabs-home" aria-selected="true">{{__('PkgWidgets::widgetType.singular')}}</a>
                        </li>

                         @if($itemRealisationTache->widgets->count() > 0 || auth()->user()?->can('create-widget'))
                        <li class="nav-item">
                            <a class="nav-link" id="widgetType-hasmany-tabs-widget-tab" data-toggle="pill" href="#widgetType-hasmany-tabs-widget" role="tab" aria-controls="widgetType-hasmany-tabs-widget" aria-selected="false">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                {{ucfirst(__('PkgWidgets::widget.plural'))}}
                            </a>
                        </li>
                        @endif

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-widgetType-tabContent">
                            <div class="tab-pane fade show active" id="widgetType-hasmany-tabs-home" role="tabpanel" aria-labelledby="widgetType-hasmany-tabs-home-tab">
                                @include('PkgWidgets::widgetType._fields')
                            </div>

                            @if($itemRealisationTache->widgets->count() > 0 || auth()->user()?->can('create-widget'))
                            <div class="tab-pane fade" id="widgetType-hasmany-tabs-widget" role="tabpanel" aria-labelledby="widgetType-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'widgetType.edit_' . $itemWidgetType->id])
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
