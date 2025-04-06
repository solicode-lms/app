{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'eMetadatum',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'eMetadatum.index' }}', 
        filterFormSelector: '#eMetadatum-crud-filter-form',
        crudSelector: '#eMetadatum-crud',
        tableSelector: '#eMetadatum-data-container',
        formSelector: '#eMetadatumForm',
        indexUrl: '{{ route('eMetadata.index') }}', 
        createUrl: '{{ route('eMetadata.create') }}',
        editUrl: '{{ route('eMetadata.edit',  ['eMetadatum' => ':id']) }}',
        showUrl: '{{ route('eMetadata.show',  ['eMetadatum' => ':id']) }}',
        storeUrl: '{{ route('eMetadata.store') }}', 
        updateAttributesUrl: '{{ route('eMetadata.updateAttributes') }}', 
        deleteUrl: '{{ route('eMetadata.destroy',  ['eMetadatum' => ':id']) }}', 
        calculationUrl:  '{{ route('eMetadata.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadatum.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eMetadatum.singular") }}',
    });
</script>

<div id="eMetadatum-crud" class="crud">
    @section('eMetadatum-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eMetadatum.singular");
    @endphp
    <x-crud-header 
        id="eMetadatum-crud-header" icon="fas fa-th-list"  
        iconColor="text-info"
        title="{{ __('PkgGapp::eMetadatum.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eMetadatum-crud-table')
    <section id="eMetadatum-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eMetadatum-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eMetadata_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                        @canany(['create-eMetadatum','import-eMetadatum','export-eMetadatum'])
                        <x-crud-actions
                            :instanceItem="$eMetadatum_instance"
                            :createPermission="'create-eMetadatum'"
                            :createRoute="route('eMetadata.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eMetadatum'"
                            :importRoute="route('eMetadata.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eMetadatum'"
                            :exportXlsxRoute="route('eMetadata.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('eMetadata.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$eMetadatum_viewTypes"
                            :viewType="$eMetadatum_viewType"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('eMetadatum-crud-filters')
                <div class="card-header">
                    <form id="eMetadatum-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eMetadata_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eMetadata_filters as $filter)
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
                        @section('eMetadatum-crud-search-bar')
                        <div id="eMetadatum-crud-search-bar"
                            class="{{ count($eMetadata_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eMetadata_search')"
                                name="eMetadata_search"
                                id="eMetadata_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eMetadatum-data-container" class="data-container">
                    @if($eMetadatum_viewType == "table")
                    @include("PkgGapp::eMetadatum._$eMetadatum_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="eMetadatum-data-container-out" >
        @if($eMetadatum_viewType == "widgets")
        @include("PkgGapp::eMetadatum._$eMetadatum_viewType")
        @endif
    </section>
    @show
</div>