{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'nationalite',
        contextKey: 'nationalite.edit_{{ $itemNationalite->id}}',
        cardTabSelector: '#card-tab-nationalite', 
        formSelector: '#nationaliteForm',
        editUrl: '{{ route('nationalites.edit',  ['nationalite' => ':id']) }}',
        indexUrl: '{{ route('nationalites.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::nationalite.singular") }}',
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
                <div id="card-tab-nationalite" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-nationalite-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-table"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="nationalite-hasmany-tabs-home-tab" data-toggle="pill" href="#nationalite-hasmany-tabs-home" role="tab" aria-controls="nationalite-hasmany-tabs-home" aria-selected="true">{{__('PkgApprenants::nationalite.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="nationalite-hasmany-tabs-apprenant-tab" data-toggle="pill" href="#nationalite-hasmany-tabs-apprenant" role="tab" aria-controls="nationalite-hasmany-tabs-apprenant" aria-selected="false">{{__('PkgApprenants::apprenant.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-nationalite-tabContent">
                            <div class="tab-pane fade show active" id="nationalite-hasmany-tabs-home" role="tabpanel" aria-labelledby="nationalite-hasmany-tabs-home-tab">
                                @include('PkgApprenants::nationalite._fields')
                            </div>

                            <div class="tab-pane fade" id="nationalite-hasmany-tabs-apprenant" role="tabpanel" aria-labelledby="nationalite-hasmany-tabs-apprenant-tab">
                                @include('PkgApprenants::apprenant._index',['isMany' => true, "edit_has_many" => false,"contextKey" => 'nationalite.edit_' . $itemNationalite->id])
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
