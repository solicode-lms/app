{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sectionWidget',
        contextKey: 'sectionWidget.edit_{{ $itemSectionWidget->id}}',
        cardTabSelector: '#card-tab-sectionWidget', 
        formSelector: '#sectionWidgetForm',
        editUrl: '{{ route('sectionWidgets.edit',  ['sectionWidget' => ':id']) }}',
        indexUrl: '{{ route('sectionWidgets.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgWidgets::sectionWidget.singular") }}',
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
                <div id="card-tab-sectionWidget" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sectionWidget-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sectionWidget-hasmany-tabs-home-tab" data-toggle="pill" href="#sectionWidget-hasmany-tabs-home" role="tab" aria-controls="sectionWidget-hasmany-tabs-home" aria-selected="true">{{__('PkgWidgets::sectionWidget.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sectionWidget-hasmany-tabs-widget-tab" data-toggle="pill" href="#sectionWidget-hasmany-tabs-widget" role="tab" aria-controls="sectionWidget-hasmany-tabs-widget" aria-selected="false">{{__('PkgWidgets::widget.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sectionWidget-tabContent">
                            <div class="tab-pane fade show active" id="sectionWidget-hasmany-tabs-home" role="tabpanel" aria-labelledby="sectionWidget-hasmany-tabs-home-tab">
                                @include('PkgWidgets::sectionWidget._fields')
                            </div>

                            <div class="tab-pane fade" id="sectionWidget-hasmany-tabs-widget" role="tabpanel" aria-labelledby="sectionWidget-hasmany-tabs-widget-tab">
                                @include('PkgWidgets::widget._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sectionWidget.edit_' . $itemSectionWidget->id])
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
