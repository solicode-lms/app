{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'etatChapitre',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatChapitre.index' }}', 
        filterFormSelector: '#etatChapitre-crud-filter-form',
        crudSelector: '#etatChapitre-crud',
        tableSelector: '#etatChapitre-data-container',
        formSelector: '#etatChapitreForm',
        indexUrl: '{{ route('etatChapitres.index') }}', 
        createUrl: '{{ route('etatChapitres.create') }}',
        editUrl: '{{ route('etatChapitres.edit',  ['etatChapitre' => ':id']) }}',
        showUrl: '{{ route('etatChapitres.show',  ['etatChapitre' => ':id']) }}',
        storeUrl: '{{ route('etatChapitres.store') }}', 
        updateAttributesUrl: '{{ route('etatChapitres.updateAttributes') }}', 
        deleteUrl: '{{ route('etatChapitres.destroy',  ['etatChapitre' => ':id']) }}', 
        calculationUrl:  '{{ route('etatChapitres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::etatChapitre.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::etatChapitre.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $etatChapitre_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatChapitre-crud" class="crud">
    @section('etatChapitre-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::etatChapitre.singular");
    @endphp
    <x-crud-header 
        id="etatChapitre-crud-header" icon="fas fa-check"  
        iconColor="text-info"
        title="{{ $etatChapitre_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatChapitre-crud-table')
    <section id="etatChapitre-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatChapitre-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatChapitres_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$etatChapitre_instance"
                                :createPermission="'create-etatChapitre'"
                                :createRoute="route('etatChapitres.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-etatChapitre'"
                                :importRoute="route('etatChapitres.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-etatChapitre'"
                                :exportXlsxRoute="route('etatChapitres.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('etatChapitres.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$etatChapitre_viewTypes"
                                :viewType="$etatChapitre_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatChapitre-crud-filters')
                <div class="card-header">
                    <form id="etatChapitre-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatChapitres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatChapitres_filters as $filter)
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
                        @section('etatChapitre-crud-search-bar')
                        <div id="etatChapitre-crud-search-bar"
                            class="{{ count($etatChapitres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatChapitres_search')"
                                name="etatChapitres_search"
                                id="etatChapitres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="etatChapitre-data-container" class="data-container">
                    @if($etatChapitre_viewType == "table")
                    @include("PkgAutoformation::etatChapitre._$etatChapitre_viewType")
                    @endif
                </div>
                @section('etatChapitre-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatChapitre")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatChapitres.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatChapitre')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatChapitres.bulkDelete') }}" 
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
     <section id="etatChapitre-data-container-out" >
        @if($etatChapitre_viewType == "widgets")
        @include("PkgAutoformation::etatChapitre._$etatChapitre_viewType")
        @endif
    </section>
    @show
</div>