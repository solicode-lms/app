{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sysColor',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysColor.index' }}', 
        filterFormSelector: '#sysColor-crud-filter-form',
        crudSelector: '#sysColor-crud',
        tableSelector: '#sysColor-data-container',
        formSelector: '#sysColorForm',
        indexUrl: '{{ route('sysColors.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sysColors.create') }}',
        editUrl: '{{ route('sysColors.edit',  ['sysColor' => ':id']) }}',
        showUrl: '{{ route('sysColors.show',  ['sysColor' => ':id']) }}',
        storeUrl: '{{ route('sysColors.store') }}', 
        updateAttributesUrl: '{{ route('sysColors.updateAttributes') }}', 
        deleteUrl: '{{ route('sysColors.destroy',  ['sysColor' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-sysColor')),
        calculationUrl:  '{{ route('sysColors.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysColor.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysColor.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sysColor_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sysColor-crud" class="crud">
    @section('sysColor-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysColor.singular");
    @endphp
    <x-crud-header 
        id="sysColor-crud-header" icon="fas fa-palette"  
        iconColor="text-info"
        title="{{ $sysColor_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysColor-crud-table')
    <section id="sysColor-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysColor-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sysColors_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sysColor_instance"
                                    :createPermission="'create-sysColor'"
                                    :createRoute="route('sysColors.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sysColor'"
                                    :importRoute="route('sysColors.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sysColor'"
                                    :exportXlsxRoute="route('sysColors.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sysColors.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sysColor_viewTypes"
                                    :viewType="$sysColor_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sysColor-crud-filters')
                <div class="card-header">
                    <form id="sysColor-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysColors_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysColors_filters as $filter)
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
                        @section('sysColor-crud-search-bar')
                        <div id="sysColor-crud-search-bar"
                            class="{{ count($sysColors_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysColors_search')"
                                name="sysColors_search"
                                id="sysColors_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysColor-data-container" class="data-container">
                    @if($sysColor_viewType != "widgets")
                    @include("Core::sysColor._$sysColor_viewType")
                    @endif
                </div>
                @section('sysColor-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sysColor")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sysColors.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sysColor')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sysColors.bulkDelete') }}" 
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
     <section id="sysColor-data-container-out" >
        @if($sysColor_viewType == "widgets")
        @include("Core::sysColor._$sysColor_viewType")
        @endif
    </section>
    @show
</div>