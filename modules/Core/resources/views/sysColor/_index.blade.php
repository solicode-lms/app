{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sysColor',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysColor.index' }}', 
        filterFormSelector: '#sysColor-crud-filter-form',
        crudSelector: '#sysColor-crud',
        tableSelector: '#sysColor-data-container',
        formSelector: '#sysColorForm',
        indexUrl: '{{ route('sysColors.index') }}', 
        createUrl: '{{ route('sysColors.create') }}',
        editUrl: '{{ route('sysColors.edit',  ['sysColor' => ':id']) }}',
        showUrl: '{{ route('sysColors.show',  ['sysColor' => ':id']) }}',
        storeUrl: '{{ route('sysColors.store') }}', 
        deleteUrl: '{{ route('sysColors.destroy',  ['sysColor' => ':id']) }}', 
        calculationUrl:  '{{ route('sysColors.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysColor.singular") }}',
    });
</script>

<div id="sysColor-crud" class="crud">
    @section('sysColor-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysColor.singular");
    @endphp
    <x-crud-header 
        id="sysColor-crud-header" icon="fas fa-palette"  
        iconColor="text-info"
        title="{{ __('Core::sysColor.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysColor-crud-table')
    <section id="sysColor-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysColor-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$sysColors_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-sysColor','import-sysColor','export-sysColor'])
                        <x-crud-actions
                            :instanceItem="$sysColor_instance"
                            :createPermission="'create-sysColor'"
                            :createRoute="route('sysColors.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-sysColor'"
                            :importRoute="route('sysColors.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-sysColor'"
                            :exportXlsxRoute="route('sysColors.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('sysColors.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('sysColor-crud-filters')
                <div class="card-header">
                    <form id="sysColor-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysColors_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysColors_filters as $filter)
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
                        @section('sysColor-crud-search-bar')
                        <div id="sysColor-crud-search-bar"
                            class="{{ count($sysColors_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysColors_search')"
                                name="sysColors_search"
                                id="sysColors_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysColor-data-container" class="data-container">
                    @include('Core::sysColor._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>