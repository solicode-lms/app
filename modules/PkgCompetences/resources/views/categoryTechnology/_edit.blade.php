{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<script>
    window.editWithTabPanelManagersConfig = window.editWithTabPanelManagersConfig || [];
    window.editWithTabPanelManagersConfig.push({
        entity_name: 'categoryTechnology',
        cardTabSelector: '#card-tab-categoryTechnology', 
        formSelector: '#categoryTechnologyForm',
        editUrl: '{{ route('categoryTechnologies.edit',  ['categoryTechnology' => ':id']) }}',
        indexUrl: '{{ route('categoryTechnologies.index') }}',
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
    });
</script>
<script>
    window.contextState = @json($contextState);
</script>

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <div id="card-tab-categoryTechnology" class="card card-info card-tabs card-workflow">
                    <div class="card-header d-flex justify-content-between p-0 pt-1">
                        <ul class="nav nav-tabs mr-auto" id="edit-categoryTechnology-tab" role="tablist">
                        <li class="pt-2 px-3">
                            <h3 class="card-title">
                                <i class="nav-icon fas fa-bolt"></i>
                            </h3>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="categoryTechnology-hasmany-tabs-home-tab" data-toggle="pill" href="#categoryTechnology-hasmany-tabs-home" role="tab" aria-controls="categoryTechnology-hasmany-tabs-home" aria-selected="true">{{__('PkgCompetences::categoryTechnology.singular')}}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="categoryTechnology-hasmany-tabs-technology-tab" data-toggle="pill" href="#categoryTechnology-hasmany-tabs-technology" role="tab" aria-controls="categoryTechnology-hasmany-tabs-technology" aria-selected="false">{{__('PkgCompetences::technology.plural')}}</a>
                        </li>

                       
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="edit-categoryTechnology-tabContent">
                            <div class="tab-pane fade show active" id="categoryTechnology-hasmany-tabs-home" role="tabpanel" aria-labelledby="categoryTechnology-hasmany-tabs-home-tab">
                                @include('PkgCompetences::categoryTechnology._fields')
                            </div>

                            <div class="tab-pane fade" id="categoryTechnology-hasmany-tabs-technology" role="tabpanel" aria-labelledby="categoryTechnology-hasmany-tabs-technology-tab">
                                @include('PkgCompetences::technology._index',['isMany' => true, "edit_has_many" => false])
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
