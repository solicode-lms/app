{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'sysColor',
        contextKey: 'sysColor.edit_' . $itemSysColor->id,
        cardTabSelector: '#card-tab-sysColor', 
        formSelector: '#sysColorForm',
        editUrl: '{{ route('sysColors.edit',  ['sysColor' => ':id']) }}',
        indexUrl: '{{ route('sysColors.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysColor.singular") }}',
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
                <div id="card-tab-sysColor" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-sysColor-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="sysColor-hasmany-tabs-home-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-home" role="tab" aria-controls="sysColor-hasmany-tabs-home" aria-selected="true">{{__('Core::sysColor.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModel-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModel" role="tab" aria-controls="sysColor-hasmany-tabs-sysModel" aria-selected="false">{{__('Core::sysModel.plural')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sysColor-hasmany-tabs-sysModule-tab" data-toggle="pill" href="#sysColor-hasmany-tabs-sysModule" role="tab" aria-controls="sysColor-hasmany-tabs-sysModule" aria-selected="false">{{__('Core::sysModule.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-sysColor-tabContent">
                            <div class="tab-pane fade show active" id="sysColor-hasmany-tabs-home" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-home-tab">
                                @include('Core::sysColor._fields')
                            </div>

                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModel" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModel-tab">
                                @include('Core::sysModel._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
                            </div>
                            <div class="tab-pane fade" id="sysColor-hasmany-tabs-sysModule" role="tabpanel" aria-labelledby="sysColor-hasmany-tabs-sysModule-tab">
                                @include('Core::sysModule._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'sysColor.edit_' . $itemSysColor->id])
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
