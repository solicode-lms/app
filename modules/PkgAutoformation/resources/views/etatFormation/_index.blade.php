{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'etatFormation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatFormation.index' }}', 
        filterFormSelector: '#etatFormation-crud-filter-form',
        crudSelector: '#etatFormation-crud',
        tableSelector: '#etatFormation-data-container',
        formSelector: '#etatFormationForm',
        indexUrl: '{{ route('etatFormations.index') }}', 
        createUrl: '{{ route('etatFormations.create') }}',
        editUrl: '{{ route('etatFormations.edit',  ['etatFormation' => ':id']) }}',
        showUrl: '{{ route('etatFormations.show',  ['etatFormation' => ':id']) }}',
        storeUrl: '{{ route('etatFormations.store') }}', 
        updateAttributesUrl: '{{ route('etatFormations.updateAttributes') }}', 
        deleteUrl: '{{ route('etatFormations.destroy',  ['etatFormation' => ':id']) }}', 
        calculationUrl:  '{{ route('etatFormations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::etatFormation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::etatFormation.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $etatFormation_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatFormation-crud" class="crud">
    @section('etatFormation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::etatFormation.singular");
    @endphp
    <x-crud-header 
        id="etatFormation-crud-header" icon="fas fa-check"  
        iconColor="text-info"
        title="{{ $etatFormation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatFormation-crud-table')
    <section id="etatFormation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatFormation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatFormations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$etatFormation_instance"
                                :createPermission="'create-etatFormation'"
                                :createRoute="route('etatFormations.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-etatFormation'"
                                :importRoute="route('etatFormations.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-etatFormation'"
                                :exportXlsxRoute="route('etatFormations.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('etatFormations.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$etatFormation_viewTypes"
                                :viewType="$etatFormation_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatFormation-crud-filters')
                <div class="card-header">
                    <form id="etatFormation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatFormations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatFormations_filters as $filter)
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
                        @section('etatFormation-crud-search-bar')
                        <div id="etatFormation-crud-search-bar"
                            class="{{ count($etatFormations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatFormations_search')"
                                name="etatFormations_search"
                                id="etatFormations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="etatFormation-data-container" class="data-container">
                    @if($etatFormation_viewType == "table")
                    @include("PkgAutoformation::etatFormation._$etatFormation_viewType")
                    @endif
                </div>
                @section('etatFormation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatFormation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatFormations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatFormation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatFormations.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    @endcan
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="etatFormation-data-container-out" >
        @if($etatFormation_viewType == "widgets")
        @include("PkgAutoformation::etatFormation._$etatFormation_viewType")
        @endif
    </section>
    @show
</div>