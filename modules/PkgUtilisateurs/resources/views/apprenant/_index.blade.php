{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'apprenant',
        filterFormSelector: '#apprenant-crud-filter-form',
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
                                <form id="apprenant-crud-filter-form" method="GET" class="row mb-3">
                                    <div id="apprenant-crud-filters" class="col-md-10 d-flex align-items-center">

                                       

                                        <div class="mr-3">
                                            <i class="fas fa-filter text-info filter-icon"  title="Réinitialiser les filtres"></i>
                                        </div>

                                        
                                        <div class="row w-100">
                        
                                            <!-- Filtres spécifiques -->
                                            @foreach ($apprenants_filters as $filter)
                                                <div class="col-md-3 mb-3">
                                                    @switch($filter['type'])
                                                        @case('text')
                                                            <input type="text" 
                                                                   name="{{ $filter['field'] }}" 
                                                                   class="form-control form-control-sm" 
                                                                   value="{{ request($filter['field']) }}" 
                                                                   placeholder="{{ ucfirst(str_replace('_', ' ', $filter['field'])) }}">
                                                            @break
                        
                                                        @case('date')
                                                            <input type="date" 
                                                                   name="{{ $filter['field'] }}" 
                                                                   class="form-control form-control-sm" 
                                                                   value="{{ request($filter['field']) }}">
                                                            @break
                                                        @case('manyToOne')
                                                            <select name="{{ $filter['field'] }}" 
                                                                    class="form-select form-control form-control-sm">
                                                                <option value="">Tous</option>
                                                                @foreach ($filter['options'] as $option)
                                                                    <option value="{{ $option['id'] }}" 
                                                                            {{ request($filter['field']) == $option['id'] ? 'selected' : '' }}>
                                                                        {{ $option['label'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @break
                                                    @endswitch
                                                </div>
                                            @endforeach
                        
                                        </div>
                                    </div>
                                    
                                    @section('crud-search-bar')
                                    @php
                                        // Dynamisation : Utiliser le nombre de filtres
                                        $filters = count($apprenants_filters);
                                    @endphp
                                    <div id="apprenant-crud-search-bar"
                                        class="{{ $filters > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                                        <x-search-bar
                                            :search="request('apprenants_search')"
                                            name="apprenants_search"
                                            id="apprenants_search"
                                            placeholder="Recherche ..."
                                        />
                                    </div>
                                    @show
                                </form>
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