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
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('eMetadata.create') }}',
        editUrl: '{{ route('eMetadata.edit',  ['eMetadatum' => ':id']) }}',
        showUrl: '{{ route('eMetadata.show',  ['eMetadatum' => ':id']) }}',
        storeUrl: '{{ route('eMetadata.store') }}', 
        updateAttributesUrl: '{{ route('eMetadata.updateAttributes') }}', 
        deleteUrl: '{{ route('eMetadata.destroy',  ['eMetadatum' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-eMetadatum')),
        calculationUrl:  '{{ route('eMetadata.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eMetadatum.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eMetadatum.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $eMetadatum_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
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
        title="{{ $eMetadatum_title }}"
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
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$eMetadata_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
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
                            </div>


                        
                        </div>
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
                @section('eMetadatum-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-eMetadatum")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('eMetadata.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-eMetadatum')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('eMetadata.bulkDelete') }}" 
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
     <section id="eMetadatum-data-container-out" >
        @if($eMetadatum_viewType == "widgets")
        @include("PkgGapp::eMetadatum._$eMetadatum_viewType")
        @endif
    </section>
    @show
</div>