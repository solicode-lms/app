{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'apprenant',
        crudSelector: '#apprenant-crud',
        tableSelector: '#apprenant-data-container',
        formSelector: '#apprenantForm',
        modalSelector : '#apprenantModal',
        indexUrl: '{{ route('apprenants.index') }}', 
        createUrl: '{{ route('apprenants.create') }}',
        editUrl: '{{ route('apprenants.edit',  ['apprenant' => ':id']) }}',
        showUrl: '{{ route('apprenants.show',  ['apprenant' => ':id']) }}',
        storeUrl: '{{ route('apprenants.store') }}', 
        deleteUrl: '{{ route('apprenants.destroy',  ['apprenant' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::apprenant.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::apprenant.singular") }}',
    });
</script>
@endpush


<div id="apprenant-crud" class="crud">
    @section('crud-header')
    <x-crud-header 
        id="apprenant-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::apprenant.plural') }}"
        :breadcrumbs="[
            ['label' => 'Gestion Utilisateurs', 'url' => '#'],
            ['label' => 'Villes']
        ]"
    />
    @show
    @section('crud-table')
    <section id="apprenant-crud-table" class="content crud-table">
        <div class="container-fluid">
                    <div class="card card-outline card-info " id="card_crud">

                        <div class="card-header row">
                            @section('crud-stats-bar')
                            <!-- Statistiques et Actions -->
                            <div class="col-sm-9">
                                <x-crud-stats-summary
                                    icon="fas fa-chart-bar text-info"
                                    :stats="$apprenants_stats"
                                />
                            </div>
                                <div class="col-sm-3">
                                    <x-crud-actions
                                        :createPermission="'create-apprenant'"
                                        :createRoute="route('apprenants.create')"
                                        :createText="__('Ajouter une apprenant')"
                                        :importPermission="'import-apprenant'"
                                        :importRoute="route('apprenants.import')"
                                        :importText="__('Importer')"
                                        :exportPermission="'export-apprenant'"
                                        :exportRoute="route('apprenants.export')"
                                        :exportText="__('Exporter')"
                                    />
                                </div>
                            @show
                        </div>



                        <div class="card-header">
                            <div class="row">
                                @section('crud-filters')
                                <div id="apprenant-crud-filters" class="col-md-10 d-flex align-items-center">
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
                                <div id="apprenant-crud-search-bar"
                                    class="{{ isset($filters) && $filters ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                                    <x-search-bar
                                        :searchQuery="$apprenant_searchQuery"
                                        name="apprenant-crud-search-input"
                                        id="apprenant-crud-search-input"
                                        placeholder="Recherche des apprenants"
                                    />
                                </div>
                                @show
                            </div>
                        </div>
                        <div id="apprenant-data-container" class="data-container">
                            @include('PkgUtilisateurs::apprenant._table')
                        </div>
                    </div>
        </div>
        </div>
        <input type="hidden" id='page' value="1">
    </section>
    @show

    @section('crud-modal')
    <!-- Modal pour Ajouter/Modifier -->
    <div class="modal fade crud-modal" id="apprenantModal" tabindex="-1" role="dialog" aria-labelledby="apprenantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div id="modal-loading"  class="d-flex justify-content-center align-items-center" style="display: none; min-height: 200px;  ">
                    <div class="spinner-border text-primary" role="status">
                    </div>
                </div>

                <!-- Contenu injecté -->
                <div id="modal-content-container" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="apprenantModalLabel"></h5>
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