{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'widget',
        contextKey: 'widget.edit_{{ $itemWidget->id}}',
        cardTabSelector: '#card-tab-widget', 
        formSelector: '#widgetForm',
        editUrl: '{{ route('widgets.edit',  ['widget' => ':id']) }}',
        indexUrl: '{{ route('widgets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::widget.singular") }}',
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
                <div id="card-tab-widget" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-widget-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="widget-hasmany-tabs-home-tab" data-toggle="pill" href="#widget-hasmany-tabs-home" role="tab" aria-controls="widget-hasmany-tabs-home" aria-selected="true">{{__('PkgWidgets::widget.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="widget-hasmany-tabs-widgetUtilisateur-tab" data-toggle="pill" href="#widget-hasmany-tabs-widgetUtilisateur" role="tab" aria-controls="widget-hasmany-tabs-widgetUtilisateur" aria-selected="false">{{__('PkgWidgets::widgetUtilisateur.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-widget-tabContent">
                            <div class="tab-pane fade show active" id="widget-hasmany-tabs-home" role="tabpanel" aria-labelledby="widget-hasmany-tabs-home-tab">
                                @include('PkgWidgets::widget._fields')
                            </div>

                            <div class="tab-pane fade" id="widget-hasmany-tabs-widgetUtilisateur" role="tabpanel" aria-labelledby="widget-hasmany-tabs-widgetUtilisateur-tab">
                                @include('PkgWidgets::widgetUtilisateur._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'widget.edit_' . $itemWidget->id])
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
