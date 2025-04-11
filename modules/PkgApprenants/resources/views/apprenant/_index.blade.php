{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'apprenant',
        dataSource: '{{ isset($dataSource) ? $dataSource : 'default' }}',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'apprenant.index' }}', 
        filterFormSelector: '#apprenant-crud-filter-form',
        crudSelector: '#apprenant-crud',
        tableSelector: '#apprenant-data-container',
        formSelector: '#apprenantForm',
        indexUrl: '{{ route('apprenants.index') }}', 
        createUrl: '{{ route('apprenants.create') }}',
        editUrl: '{{ route('apprenants.edit',  ['apprenant' => ':id']) }}',
        showUrl: '{{ route('apprenants.show',  ['apprenant' => ':id']) }}',
        storeUrl: '{{ route('apprenants.store') }}', 
        updateAttributesUrl: '{{ route('apprenants.updateAttributes') }}', 
        deleteUrl: '{{ route('apprenants.destroy',  ['apprenant' => ':id']) }}', 
        calculationUrl:  '{{ route('apprenants.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::apprenant.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::apprenant.singular") }}',
    });
</script>

<div id="apprenant-crud" class="crud">
    @section('apprenant-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::apprenant.singular");
    @endphp
    <x-crud-header 
        id="apprenant-crud-header" icon="fas fa-id-card"  
        iconColor="text-info"
        title="{{ $apprenant_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('apprenant-crud-table')
    <section id="apprenant-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('apprenant-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$apprenants_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$apprenant_instance"
                            :createPermission="'create-apprenant'"
                            :createRoute="route('apprenants.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-apprenant'"
                            :importRoute="route('apprenants.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-apprenant'"
                            :exportXlsxRoute="route('apprenants.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('apprenants.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$apprenant_viewTypes"
                            :viewType="$apprenant_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('apprenant-crud-filters')
                <div class="card-header">
                    <form id="apprenant-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($apprenants_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($apprenants_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" 
                                    :targetDynamicDropdown="isset($filter['targetDynamicDropdown']) ? $filter['targetDynamicDropdown'] : null"
                                    :targetDynamicDropdownApiUrl="isset($filter['targetDynamicDropdownApiUrl']) ? $filter['targetDynamicDropdownApiUrl'] : null" 
                                    :targetDynamicDropdownFilter="isset($filter['targetDynamicDropdownFilter']) ? $filter['targetDynamicDropdownFilter'] : null" />
                            @endforeach
                        </x-filter-group>
                        @section('apprenant-crud-search-bar')
                        <div id="apprenant-crud-search-bar"
                            class="{{ count($apprenants_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('apprenants_search')"
                                name="apprenants_search"
                                id="apprenants_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="apprenant-data-container" class="data-container">
                    @if($apprenant_viewType == "table")
                    @include("PkgApprenants::apprenant._$apprenant_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="apprenant-data-container-out" >
        @if($apprenant_viewType == "widgets")
        @include("PkgApprenants::apprenant._$apprenant_viewType")
        @endif
    </section>
    @show
</div>