{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('script')
@parent
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        entity_name: 'categoryTechnology',
        crudSelector: '#categoryTechnology_crud',
        indexUrl: '{{ route('categoryTechnologies.index') }}', 
        createUrl: '{{ route('categoryTechnologies.create') }}',
        editUrl: '{{ route('categoryTechnologies.edit', ['categoryTechnology' => ':id']) }}',
        showUrl: '{{ route('categoryTechnologies.show', ['categoryTechnology' => ':id']) }}',
        storeUrl: '{{ route('categoryTechnologies.store') }}', 
        deleteUrl: '{{ route('categoryTechnologies.destroy', ['categoryTechnology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::categoryTechnology.singular") }}',
    });
</script>
@endsection
<div id="categoryTechnology_crud">
    <div class="content-header">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('success') }}.
            </div>
        @endif
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        {{ curd_index_title('PkgCompetences::categoryTechnology') }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        @can('create-categoryTechnology')
                        <button type="button" class="btn btn-info btn-sm addEntityButton" data-target="#categoryTechnologyModal">
                            <i class="fas fa-plus"></i> {{ __('Core::msg.add') }}
                        </button>

                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="content" id="section_crud">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="card_crud">
                        <div class="card-header col-md-12">
                            <div class="p-0">
                                <div class="input-group input-group-sm float-sm-right col-md-3 p-0">
                                    <input type="text" value="{{ $categoryTechnology_searchQuery ?? '' }}" name="crud_search_input" id="crud_search_input"
                                           class="form-control float-right" placeholder="Recherche">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="data-container">
                            @include('PkgCompetences::categoryTechnology._table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id='page' value="1">
    </section>


<!-- Modal pour Ajouter/Modifier -->
<div class="modal fade crud-modal" id="categoryTechnologyModal" tabindex="-1" role="dialog" aria-labelledby="categoryTechnologyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div id="modal-loading"  class="d-flex justify-content-center align-items-center" style="display: none; min-height: 200px;  ">
                <div class="spinner-border text-primary" role="status">
                </div>
            </div>

            <!-- Contenu injecté -->
            <div id="modal-content-container" style="display: none;">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryTechnologyModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
</div>


</div>