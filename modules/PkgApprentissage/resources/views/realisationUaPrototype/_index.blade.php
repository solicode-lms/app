{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id  :  '{{ $parent_manager_id  ?? 'null' }}',
        editOnFullScreen : false,
        entity_name: 'realisationUaPrototype',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationUaPrototype.index' }}', 
        filterFormSelector: '#realisationUaPrototype-crud-filter-form',
        crudSelector: '#realisationUaPrototype-crud',
        tableSelector: '#realisationUaPrototype-data-container',
        formSelector: '#realisationUaPrototypeForm',
        indexUrl: '{{ route('realisationUaPrototypes.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationUaPrototypes.create') }}',
        editUrl: '{{ route('realisationUaPrototypes.edit',  ['realisationUaPrototype' => ':id']) }}',
        showUrl: '{{ route('realisationUaPrototypes.show',  ['realisationUaPrototype' => ':id']) }}',
        getEntityUrl: '{{ route("realisationUaPrototypes.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('realisationUaPrototypes.store') }}', 
        updateAttributesUrl: '{{ route('realisationUaPrototypes.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationUaPrototypes.destroy',  ['realisationUaPrototype' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationUaPrototype')),
        calculationUrl:  '{{ route('realisationUaPrototypes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::realisationUaPrototype.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationUaPrototype.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationUaPrototype_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationUaPrototype-crud" class="crud">
    @section('realisationUaPrototype-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::realisationUaPrototype.singular");
    @endphp
    <x-crud-header 
        id="realisationUaPrototype-crud-header" icon="fas fa-cog"  
        iconColor="text-info"
        title="{{ $realisationUaPrototype_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationUaPrototype-crud-table')
    <section id="realisationUaPrototype-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationUaPrototype-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationUaPrototypes_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationUaPrototype_instance"
                                    :createPermission="'create-realisationUaPrototype'"
                                    :createRoute="route('realisationUaPrototypes.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationUaPrototype'"
                                    :importRoute="route('realisationUaPrototypes.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationUaPrototype'"
                                    :exportXlsxRoute="route('realisationUaPrototypes.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationUaPrototypes.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationUaPrototype_viewTypes"
                                    :viewType="$realisationUaPrototype_viewType"
                                    :total="$realisationUaPrototypes_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationUaPrototype-crud-filters')
                @if(!empty($realisationUaPrototypes_total) &&  $realisationUaPrototypes_total > 5)
                <div class="card-header">
                    <form id="realisationUaPrototype-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationUaPrototypes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationUaPrototypes_filters as $filter)
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
                        @section('realisationUaPrototype-crud-search-bar')
                        <div id="realisationUaPrototype-crud-search-bar"
                            class="{{ count($realisationUaPrototypes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationUaPrototypes_search')"
                                name="realisationUaPrototypes_search"
                                id="realisationUaPrototypes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationUaPrototype-data-container" class="data-container">
                    @if($realisationUaPrototype_viewType != "widgets")
                    @include("PkgApprentissage::realisationUaPrototype._$realisationUaPrototype_viewType")
                    @endif
                </div>
                @section('realisationUaPrototype-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationUaPrototype")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationUaPrototypes.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationUaPrototype')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationUaPrototypes.bulkDelete') }}" 
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
     <section id="realisationUaPrototype-data-container-out" >
        @if($realisationUaPrototype_viewType == "widgets")
        @include("PkgApprentissage::realisationUaPrototype._$realisationUaPrototype_viewType")
        @endif
    </section>
    @show
</div>