{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'natureLivrable',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'natureLivrable.index' }}', 
        filterFormSelector: '#natureLivrable-crud-filter-form',
        crudSelector: '#natureLivrable-crud',
        tableSelector: '#natureLivrable-data-container',
        formSelector: '#natureLivrableForm',
        indexUrl: '{{ route('natureLivrables.index') }}', 
        createUrl: '{{ route('natureLivrables.create') }}',
        editUrl: '{{ route('natureLivrables.edit',  ['natureLivrable' => ':id']) }}',
        showUrl: '{{ route('natureLivrables.show',  ['natureLivrable' => ':id']) }}',
        storeUrl: '{{ route('natureLivrables.store') }}', 
        updateAttributesUrl: '{{ route('natureLivrables.updateAttributes') }}', 
        deleteUrl: '{{ route('natureLivrables.destroy',  ['natureLivrable' => ':id']) }}', 
        calculationUrl:  '{{ route('natureLivrables.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::natureLivrable.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $natureLivrable_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="natureLivrable-crud" class="crud">
    @section('natureLivrable-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::natureLivrable.singular");
    @endphp
    <x-crud-header 
        id="natureLivrable-crud-header" icon="fas fa-file-archive"  
        iconColor="text-info"
        title="{{ $natureLivrable_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('natureLivrable-crud-table')
    <section id="natureLivrable-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('natureLivrable-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-8">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$natureLivrables_stats"
                        />
                    </div>
                    <div class="col-sm-4">
                      
                        <x-crud-actions
                            :instanceItem="$natureLivrable_instance"
                            :createPermission="'create-natureLivrable'"
                            :createRoute="route('natureLivrables.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-natureLivrable'"
                            :importRoute="route('natureLivrables.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-natureLivrable'"
                            :exportXlsxRoute="route('natureLivrables.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('natureLivrables.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                            :viewTypes="$natureLivrable_viewTypes"
                            :viewType="$natureLivrable_viewType"
                        />
                    
                    </div>
                </div>
                @show
                @section('natureLivrable-crud-filters')
                <div class="card-header">
                    <form id="natureLivrable-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($natureLivrables_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($natureLivrables_filters as $filter)
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
                        @section('natureLivrable-crud-search-bar')
                        <div id="natureLivrable-crud-search-bar"
                            class="{{ count($natureLivrables_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('natureLivrables_search')"
                                name="natureLivrables_search"
                                id="natureLivrables_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="natureLivrable-data-container" class="data-container">
                    @if($natureLivrable_viewType == "table")
                    @include("PkgCreationProjet::natureLivrable._$natureLivrable_viewType")
                    @endif
                </div>
            </div>
        </div>
    </section>
     <section id="natureLivrable-data-container-out" >
        @if($natureLivrable_viewType == "widgets")
        @include("PkgCreationProjet::natureLivrable._$natureLivrable_viewType")
        @endif
    </section>
    @show
</div>