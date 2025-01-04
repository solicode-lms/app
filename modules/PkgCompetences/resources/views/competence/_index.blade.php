{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'competence',
        crudSelector: '#competence-crud',
        tableSelector: '#competence-data-container',
        formSelector: '#competenceForm',
        modalSelector : '#competenceModal',
        indexUrl: '{{ route('competences.index') }}', 
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit',  ['competence' => ':id']) }}',
        showUrl: '{{ route('competences.show',  ['competence' => ':id']) }}',
        storeUrl: '{{ route('competences.store') }}', 
        deleteUrl: '{{ route('competences.destroy',  ['competence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
    });
</script>
@endpush


<div id="competence-crud" class="crud">
    @section('crud-header')
    <x-crud-header 
        id="competence-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::competence.plural') }}"
        :breadcrumbs="[
            ['label' => 'Gestion Utilisateurs', 'url' => '#'],
            ['label' => 'Villes']
        ]"
    />
    @show
    @section('crud-table')
    <section id="competence-crud-table" class="content crud-table">
        <div class="container-fluid">
                    <div class="card card-outline card-info " id="card_crud">

                        <div class="card-header row">
                            @section('crud-stats-bar')
                            <!-- Statistiques et Actions -->
                            <div class="col-sm-9">
                                <x-crud-stats-summary
                                    icon="fas fa-chart-bar text-info"
                                    :stats="$competences_stats"
                                />
                            </div>
                                <div class="col-sm-3">
                                    <x-crud-actions
                                        :createPermission="'create-competence'"
                                        :createRoute="route('competences.create')"
                                        :createText="__('Ajouter une competence')"
                                        :importPermission="'import-competence'"
                                        :importRoute="route('competences.import')"
                                        :importText="__('Importer')"
                                        :exportPermission="'export-competence'"
                                        :exportRoute="route('competences.export')"
                                        :exportText="__('Exporter')"
                                    />
                                </div>
                            @show
                        </div>



                        <div class="card-header">
                            <div class="row">
                                @section('crud-filters')
                                <div id="competence-crud-filters" class="col-md-10 d-flex align-items-center">
                                    <h5 class="mr-3"><i class="fas fa-filter text-info"></i></h5>
                                    <div class="row w-100">
                                        <div class="col-md-3">
                                            <select class="form-control form-control-sm" id="stockFilter">
                                                <option value="">Stock</option>
                                                <option value="in-stock">En stock</option>
                                                <option value="out-of-stock">Hors stock</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm" id="minPrice" placeholder="Prix min">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control form-control-sm" id="maxPrice" placeholder="Prix max">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control form-control-sm" id="otherFilter" placeholder="Autre filtre">
                                        </div>
                                    </div>
                                </div>
                                @show
                                @section('crud-search-bar')
                                @php
                                    $filters = 3
                                @endphp
                                <div id="competence-crud-search-bar"
                                    class="{{ isset($filters) && $filters ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                                    <x-search-bar
                                        :searchQuery="$competence_searchQuery"
                                        name="competence-crud-search-input"
                                        id="competence-crud-search-input"
                                        placeholder="Recherche des competences"
                                    />
                                </div>
                                @show
                            </div>
                        </div>
                        <div id="competence-data-container" class="data-container">
                            @include('PkgCompetences::competence._table')
                        </div>
                    </div>
        </div>
        </div>
        <input type="hidden" id='page' value="1">
    </section>
    @show

    @section('crud-modal')
    <!-- Modal pour Ajouter/Modifier -->
    <div class="modal fade crud-modal" id="competenceModal" tabindex="-1" role="dialog" aria-labelledby="competenceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div id="modal-loading"  class="d-flex justify-content-center align-items-center" style="display: none; min-height: 200px;  ">
                    <div class="spinner-border text-primary" role="status">
                    </div>
                </div>

                <!-- Contenu injecté -->
                <div id="modal-content-container" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="competenceModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </div>
    @show

</div>