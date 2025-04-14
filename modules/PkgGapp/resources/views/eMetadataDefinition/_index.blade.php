{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'eMetadataDefinition',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'eMetadataDefinition.index' }}', 
        filterFormSelector: '#eMetadataDefinition-crud-filter-form',
        crudSelector: '#eMetadataDefinition-crud',
        tableSelector: '#eMetadataDefinition-data-container',
        formSelector: '#eMetadataDefinitionForm',
        indexUrl: '{{ route('eMetadataDefinitions.index') }}', 
        createUrl: '{{ route('eMetadataDefinitions.create') }}',
        editUrl: '{{ route('eMetadataDefinitions.edit',  ['eMetadataDefinition' => ':id']) }}',
        showUrl: '{{ route('eMetadataDefinitions.show',  ['eMetadataDefinition' => ':id']) }}',
        storeUrl: '{{ route('eMetadataDefinitions.store') }}', 
        updateAttributesUrl: '{{ route('eMetadataDefinitions.updateAttributes') }}', 
        deleteUrl: '{{ route('eMetadataDefinitions.destroy',  ['eMetadataDefinition' => ':id']) }}', 
        calculationUrl:  '{{ route('eMetadataDefinitions.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadataDefinition.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eMetadataDefinition.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $eMetadataDefinition_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="eMetadataDefinition-crud" class="crud">
    @section('eMetadataDefinition-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eMetadataDefinition.singular");
    @endphp
    <x-crud-header 
        id="eMetadataDefinition-crud-header" icon="fas fa-database"  
        iconColor="text-info"
        title="{{ $eMetadataDefinition_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eMetadataDefinition-crud-table')
    <section id="eMetadataDefinition-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eMetadataDefinition-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$eMetadataDefinitions_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$eMetadataDefinition_instance"
                            :createPermission="'create-eMetadataDefinition'"
                            :createRoute="route('eMetadataDefinitions.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-eMetadataDefinition'"
                            :importRoute="route('eMetadataDefinitions.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-eMetadataDefinition'"
                            :exportXlsxRoute="route('eMetadataDefinitions.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('eMetadataDefinitions.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$eMetadataDefinition_viewTypes"
                            :viewType="$eMetadataDefinition_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('eMetadataDefinition-crud-filters')
                <div class="card-header">
                    <form id="eMetadataDefinition-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eMetadataDefinitions_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eMetadataDefinitions_filters as $filter)
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
                        @section('eMetadataDefinition-crud-search-bar')
                        <div id="eMetadataDefinition-crud-search-bar"
                            class="{{ count($eMetadataDefinitions_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eMetadataDefinitions_search')"
                                name="eMetadataDefinitions_search"
                                id="eMetadataDefinitions_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="eMetadataDefinition-data-container" class="data-container">
                    @if($eMetadataDefinition_viewType == "table")
                    @include("PkgGapp::eMetadataDefinition._$eMetadataDefinition_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="eMetadataDefinition-data-container-out" >
        @if($eMetadataDefinition_viewType == "widgets")
        @include("PkgGapp::eMetadataDefinition._$eMetadataDefinition_viewType")
        @endif
    </section>
    @show
</div>