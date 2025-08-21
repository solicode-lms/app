{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'sysModule',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sysModule.index' }}', 
        filterFormSelector: '#sysModule-crud-filter-form',
        crudSelector: '#sysModule-crud',
        tableSelector: '#sysModule-data-container',
        formSelector: '#sysModuleForm',
        indexUrl: '{{ route('sysModules.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sysModules.create') }}',
        editUrl: '{{ route('sysModules.edit',  ['sysModule' => ':id']) }}',
        fieldMetaUrl: '{{ route('sysModules.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('sysModules.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('sysModules.show',  ['sysModule' => ':id']) }}',
        getEntityUrl: '{{ route("sysModules.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('sysModules.store') }}', 
        updateAttributesUrl: '{{ route('sysModules.updateAttributes') }}', 
        deleteUrl: '{{ route('sysModules.destroy',  ['sysModule' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-sysModule')),
        calculationUrl:  '{{ route('sysModules.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::sysModule.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sysModule_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sysModule-crud" class="crud">
    @section('sysModule-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysModule.singular");
    @endphp
    <x-crud-header 
        id="sysModule-crud-header" icon="fas fa-box"  
        iconColor="text-info"
        title="{{ $sysModule_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sysModule-crud-table')
    <section id="sysModule-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sysModule-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sysModules_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sysModule_instance"
                                    :createPermission="'create-sysModule'"
                                    :createRoute="route('sysModules.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sysModule'"
                                    :importRoute="route('sysModules.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sysModule'"
                                    :exportXlsxRoute="route('sysModules.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sysModules.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sysModule_viewTypes"
                                    :viewType="$sysModule_viewType"
                                    :total="$sysModules_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sysModule-crud-filters')
                @if(!empty($sysModules_total) &&  $sysModules_total > 5)
                <div class="card-header">
                    <form id="sysModule-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sysModules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sysModules_filters as $filter)
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
                        @section('sysModule-crud-search-bar')
                        <div id="sysModule-crud-search-bar"
                            class="{{ count($sysModules_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysModules_search')"
                                name="sysModules_search"
                                id="sysModules_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="sysModule-data-container" class="data-container">
                    @if($sysModule_viewType != "widgets")
                    @include("Core::sysModule._$sysModule_viewType")
                    @endif
                </div>
                @section('sysModule-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sysModule")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sysModules.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sysModule')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sysModules.bulkDelete') }}" 
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
     <section id="sysModule-data-container-out" >
        @if($sysModule_viewType == "widgets")
        @include("Core::sysModule._$sysModule_viewType")
        @endif
    </section>
    @show
</div>