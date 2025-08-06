{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        parent_manager  :  '{{ $parent_manager  ?? 'null' }}',
        editOnFullScreen : false,
        entity_name: 'realisationUaProjet',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationUaProjet.index' }}', 
        filterFormSelector: '#realisationUaProjet-crud-filter-form',
        crudSelector: '#realisationUaProjet-crud',
        tableSelector: '#realisationUaProjet-data-container',
        formSelector: '#realisationUaProjetForm',
        indexUrl: '{{ route('realisationUaProjets.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationUaProjets.create') }}',
        editUrl: '{{ route('realisationUaProjets.edit',  ['realisationUaProjet' => ':id']) }}',
        showUrl: '{{ route('realisationUaProjets.show',  ['realisationUaProjet' => ':id']) }}',
        storeUrl: '{{ route('realisationUaProjets.store') }}', 
        updateAttributesUrl: '{{ route('realisationUaProjets.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationUaProjets.destroy',  ['realisationUaProjet' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationUaProjet')),
        calculationUrl:  '{{ route('realisationUaProjets.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::realisationUaProjet.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationUaProjet.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationUaProjet_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationUaProjet-crud" class="crud">
    @section('realisationUaProjet-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::realisationUaProjet.singular");
    @endphp
    <x-crud-header 
        id="realisationUaProjet-crud-header" icon="fas fa-cogs"  
        iconColor="text-info"
        title="{{ $realisationUaProjet_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationUaProjet-crud-table')
    <section id="realisationUaProjet-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationUaProjet-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationUaProjets_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationUaProjet_instance"
                                    :createPermission="'create-realisationUaProjet'"
                                    :createRoute="route('realisationUaProjets.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationUaProjet'"
                                    :importRoute="route('realisationUaProjets.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationUaProjet'"
                                    :exportXlsxRoute="route('realisationUaProjets.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationUaProjets.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationUaProjet_viewTypes"
                                    :viewType="$realisationUaProjet_viewType"
                                    :total="$realisationUaProjets_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationUaProjet-crud-filters')
                @if(!empty($realisationUaProjets_total) &&  $realisationUaProjets_total > 5)
                <div class="card-header">
                    <form id="realisationUaProjet-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationUaProjets_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationUaProjets_filters as $filter)
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
                        @section('realisationUaProjet-crud-search-bar')
                        <div id="realisationUaProjet-crud-search-bar"
                            class="{{ count($realisationUaProjets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationUaProjets_search')"
                                name="realisationUaProjets_search"
                                id="realisationUaProjets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationUaProjet-data-container" class="data-container">
                    @if($realisationUaProjet_viewType != "widgets")
                    @include("PkgApprentissage::realisationUaProjet._$realisationUaProjet_viewType")
                    @endif
                </div>
                @section('realisationUaProjet-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationUaProjet")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationUaProjets.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationUaProjet')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationUaProjets.bulkDelete') }}" 
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
     <section id="realisationUaProjet-data-container-out" >
        @if($realisationUaProjet_viewType == "widgets")
        @include("PkgApprentissage::realisationUaProjet._$realisationUaProjet_viewType")
        @endif
    </section>
    @show
</div>