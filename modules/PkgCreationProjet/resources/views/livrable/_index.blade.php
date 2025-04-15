{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'livrable',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'livrable.index' }}', 
        filterFormSelector: '#livrable-crud-filter-form',
        crudSelector: '#livrable-crud',
        tableSelector: '#livrable-data-container',
        formSelector: '#livrableForm',
        indexUrl: '{{ route('livrables.index') }}', 
        createUrl: '{{ route('livrables.create') }}',
        editUrl: '{{ route('livrables.edit',  ['livrable' => ':id']) }}',
        showUrl: '{{ route('livrables.show',  ['livrable' => ':id']) }}',
        storeUrl: '{{ route('livrables.store') }}', 
        updateAttributesUrl: '{{ route('livrables.updateAttributes') }}', 
        deleteUrl: '{{ route('livrables.destroy',  ['livrable' => ':id']) }}', 
        calculationUrl:  '{{ route('livrables.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::livrable.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCreationProjet::livrable.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $livrable_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="livrable-crud" class="crud">
    @section('livrable-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::livrable.singular");
    @endphp
    <x-crud-header 
        id="livrable-crud-header" icon="fas fa-file-alt"  
        iconColor="text-info"
        title="{{ $livrable_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('livrable-crud-table')
    <section id="livrable-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('livrable-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$livrables_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$livrable_instance"
                                :createPermission="'create-livrable'"
                                :createRoute="route('livrables.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-livrable'"
                                :importRoute="route('livrables.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-livrable'"
                                :exportXlsxRoute="route('livrables.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('livrables.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$livrable_viewTypes"
                                :viewType="$livrable_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('livrable-crud-filters')
                <div class="card-header">
                    <form id="livrable-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($livrables_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($livrables_filters as $filter)
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
                        @section('livrable-crud-search-bar')
                        <div id="livrable-crud-search-bar"
                            class="{{ count($livrables_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('livrables_search')"
                                name="livrables_search"
                                id="livrables_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="livrable-data-container" class="data-container">
                    @if($livrable_viewType == "table")
                    @include("PkgCreationProjet::livrable._$livrable_viewType")
                    @endif
                </div>
                @section('realisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('livrables.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('livrables.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="livrable-data-container-out" >
        @if($livrable_viewType == "widgets")
        @include("PkgCreationProjet::livrable._$livrable_viewType")
        @endif
    </section>
    @show
</div>