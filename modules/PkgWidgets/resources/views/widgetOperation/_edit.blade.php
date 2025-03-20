{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'widgetOperation',
        contextKey: 'widgetOperation.edit_{{ $itemWidgetOperation->id}}',
        cardTabSelector: '#card-tab-widgetOperation', 
        formSelector: '#widgetOperationForm',
        editUrl: '{{ route('widgetOperations.edit',  ['widgetOperation' => ':id']) }}',
        indexUrl: '{{ route('widgetOperations.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widgetOperation.singular") }}',
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
                <div id="card-tab-widgetOperation" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-widgetOperation-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-calculator"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="widgetOperation-hasmany-tabs-home-tab" data-toggle="pill" href="#widgetOperation-hasmany-tabs-home" role="tab" aria-controls="widgetOperation-hasmany-tabs-home" aria-selected="true">{{__('PkgWidgets::widgetOperation.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="widgetOperation-hasmany-tabs-widget-tab" data-toggle="pill" href="#widgetOperation-hasmany-tabs-widget" role="tab" aria-controls="widgetOperation-hasmany-tabs-widget" aria-selected="false">{{__('PkgWidgets::widget.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-widgetOperation-tabContent">
                            <div class="tab-pane fade show active" id="widgetOperation-hasmany-tabs-home" role="tabpanel" aria-labelledby="widgetOperation-hasmany-tabs-home-tab">
                                @include('PkgWidgets::widgetOperation._fields')
                            </div>

                            <div class="tab-pane fade" id="widgetOperation-hasmany-tabs-widget" role="tabpanel" aria-labelledby="widgetOperation-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'widgetOperation.edit_' . $itemWidgetOperation->id])
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
