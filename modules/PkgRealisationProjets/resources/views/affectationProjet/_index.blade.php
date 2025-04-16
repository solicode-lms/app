{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : true,
        entity_name: 'affectationProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'affectationProjet.index' }}', 
        filterFormSelector: '#affectationProjet-crud-filter-form',
        crudSelector: '#affectationProjet-crud',
        tableSelector: '#affectationProjet-data-container',
        formSelector: '#affectationProjetForm',
        indexUrl: '{{ route('affectationProjets.index') }}', 
        createUrl: '{{ route('affectationProjets.create') }}',
        editUrl: '{{ route('affectationProjets.edit',  ['affectationProjet' => ':id']) }}',
        showUrl: '{{ route('affectationProjets.show',  ['affectationProjet' => ':id']) }}',
        storeUrl: '{{ route('affectationProjets.store') }}', 
        updateAttributesUrl: '{{ route('affectationProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('affectationProjets.destroy',  ['affectationProjet' => ':id']) }}', 
        calculationUrl:  '{{ route('affectationProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::affectationProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::affectationProjet.singular") }}',
    });
</script>
<script>
    window.modalTitle = '{{ $affectationProjet_title }}'
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="affectationProjet-crud" class="crud">
    @section('affectationProjet-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::affectationProjet.singular");
    @endphp
    <x-crud-header 
        id="affectationProjet-crud-header" icon="fas fa-user-check"  
        iconColor="text-info"
        title="{{ $affectationProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('affectationProjet-crud-table')
    <section id="affectationProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('affectationProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$affectationProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$affectationProjet_instance"
                                :createPermission="'create-affectationProjet'"
                                :createRoute="route('affectationProjets.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-affectationProjet'"
                                :importRoute="route('affectationProjets.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-affectationProjet'"
                                :exportXlsxRoute="route('affectationProjets.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('affectationProjets.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$affectationProjet_viewTypes"
                                :viewType="$affectationProjet_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('affectationProjet-crud-filters')
                <div class="card-header">
                    <form id="affectationProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($affectationProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($affectationProjets_filters as $filter)
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
                        @section('affectationProjet-crud-search-bar')
                        <div id="affectationProjet-crud-search-bar"
                            class="{{ count($affectationProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('affectationProjets_search')"
                                name="affectationProjets_search"
                                id="affectationProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="affectationProjet-data-container" class="data-container">
                    @if($affectationProjet_viewType == "table")
                    @include("PkgRealisationProjets::affectationProjet._$affectationProjet_viewType")
                    @endif
                </div>
                @section('affectationProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-affectationProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('affectationProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-affectationProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('affectationProjets.bulkDelete') }}" 
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
     <section id="affectationProjet-data-container-out" >
        @if($affectationProjet_viewType == "widgets")
        @include("PkgRealisationProjets::affectationProjet._$affectationProjet_viewType")
        @endif
    </section>
    @show
</div>