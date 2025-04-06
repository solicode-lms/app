{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'formation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'formation.index' }}', 
        filterFormSelector: '#formation-crud-filter-form',
        crudSelector: '#formation-crud',
        tableSelector: '#formation-data-container',
        formSelector: '#formationForm',
        indexUrl: '{{ route('formations.index') }}', 
        createUrl: '{{ route('formations.create') }}',
        editUrl: '{{ route('formations.edit',  ['formation' => ':id']) }}',
        showUrl: '{{ route('formations.show',  ['formation' => ':id']) }}',
        storeUrl: '{{ route('formations.store') }}', 
        updateAttributesUrl: '{{ route('formations.updateAttributes') }}', 
        deleteUrl: '{{ route('formations.destroy',  ['formation' => ':id']) }}', 
        calculationUrl:  '{{ route('formations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::formation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::formation.singular") }}',
    });
</script>

<div id="formation-crud" class="crud">
    @section('formation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::formation.singular");
    @endphp
    <x-crud-header 
        id="formation-crud-header" icon="fas fa-chalkboard-teacher"  
        iconColor="text-info"
        title="{{ __('PkgAutoformation::formation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('formation-crud-table')
    <section id="formation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('formation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$formations_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-formation','import-formation','export-formation'])
                        <x-crud-actions
                            :instanceItem="$formation_instance"
                            :createPermission="'create-formation'"
                            :createRoute="route('formations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-formation'"
                            :importRoute="route('formations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-formation'"
                            :exportXlsxRoute="route('formations.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('formations.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$formation_viewTypes"
                            :viewType="$formation_viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('formation-crud-filters')
                <div class="card-header">
                    <form id="formation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($formations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($formations_filters as $filter)
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
                        @section('formation-crud-search-bar')
                        <div id="formation-crud-search-bar"
                            class="{{ count($formations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('formations_search')"
                                name="formations_search"
                                id="formations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="formation-data-container" class="data-container">
                    @if($formation_viewType == "table")
                    @include("PkgAutoformation::formation._$formation_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="formation-data-container-out" >
        @if($formation_viewType == "widgets")
        @include("PkgAutoformation::formation._$formation_viewType")
        @endif
    </section>
    @show
</div>