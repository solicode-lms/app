@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'ville',
        crudSelector: '#ville-crud',
        tableSelector: '#ville-data-container',
        formSelector: '#villeForm',
        modalSelector : '#villeModal',
        crudSelector: '#ville-crud',
        indexUrl: '{{ route('villes.index') }}', 
        createUrl: '{{ route('villes.create') }}',
        editUrl: '{{ route('villes.edit',  ['ville' => ':id']) }}',
        showUrl: '{{ route('villes.show',  ['ville' => ':id']) }}',
        storeUrl: '{{ route('villes.store') }}', 
        deleteUrl: '{{ route('villes.destroy',  ['ville' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
    });
</script>
@endpush

<div id="ville-crud" class="crud">
    @section('crud-header')
    <x-crud-header 
        id="ville-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::ville.plural') }}"
        :breadcrumbs="[
            ['label' => 'Gestion Utilisateurs', 'url' => '#'],
            ['label' => 'Villes']
        ]"
    />
    @show
    @section('crud-table')
    <section id="ville-crud-table" class="content crud-table">
        <div class="container-fluid">
                    <div class="card card-outline card-info " id="card_crud">

                        <div class="card-header row">
                            @section('crud-stats-bar')
                            <!-- Statistiques et Actions -->
                            <div class="col-sm-9">
                                <x-crud-stats-summary
                                    icon="fas fa-chart-bar text-info"
                                    :stats="[
                                        ['icon' => 'fas fa-box', 'label' => 'Total', 'value' => 120],
                                        ['icon' => 'fas fa-check', 'label' => 'En stock', 'value' => 80],
                                        ['icon' => 'fas fa-times', 'label' => 'Hors stock', 'value' => 20]
                                    ]"
                                />
                            </div>
                                <div class="col-sm-3">
                                    <x-crud-actions
                                        :createPermission="'create-ville'"
                                        :createRoute="route('villes.create')"
                                        :createText="__('Ajouter une ville')"
                                        :importPermission="'import-ville'"
                                        :importRoute="route('villes.import')"
                                        :importText="__('Importer')"
                                        :exportPermission="'export-ville'"
                                        :exportRoute="route('villes.export')"
                                        :exportText="__('Exporter')"
                                    />
                                </div>
                            @show
                        </div>



                        <div class="card-header">
                            <div class="row">
                                @section('crud-filters')
                                <div id="ville-crud-filters" class="col-md-10 d-flex align-items-center">
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
                                <div id="ville-crud-search-bar"
                                    class="{{ isset($filters) && $filters ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                                    <x-search-bar
                                        :searchQuery="$ville_searchQuery"
                                        name="ville-crud-search-input"
                                        id="ville-crud-search-input"
                                        placeholder="Recherche des villes"
                                    />
                                </div>
                                @show
                            </div>
                        </div>
                        <div id="ville-data-container" class="data-container">
                            @include('PkgUtilisateurs::ville._table')
                        </div>
                    </div>
        </div>
        </div>
        <input type="hidden" id='page' value="1">
    </section>
    @show

    @section('crud-modal')
    <!-- Modal pour Ajouter/Modifier -->
    <div class="modal fade crud-modal" id="villeModal" tabindex="-1" role="dialog" aria-labelledby="villeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div id="modal-loading"  class="d-flex justify-content-center align-items-center" style="display: none; min-height: 200px;  ">
                    <div class="spinner-border text-primary" role="status">
                    </div>
                </div>

                <!-- Contenu injecté -->
                <div id="modal-content-container" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="villeModalLabel"></h5>
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