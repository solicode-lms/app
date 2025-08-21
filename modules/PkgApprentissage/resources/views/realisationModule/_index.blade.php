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
        entity_name: 'realisationModule',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'realisationModule.index' }}', 
        filterFormSelector: '#realisationModule-crud-filter-form',
        crudSelector: '#realisationModule-crud',
        tableSelector: '#realisationModule-data-container',
        formSelector: '#realisationModuleForm',
        indexUrl: '{{ route('realisationModules.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('realisationModules.create') }}',
        editUrl: '{{ route('realisationModules.edit',  ['realisationModule' => ':id']) }}',
        fieldMetaUrl: '{{ route('realisationModules.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('realisationModules.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('realisationModules.show',  ['realisationModule' => ':id']) }}',
        getEntityUrl: '{{ route("realisationModules.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('realisationModules.store') }}', 
        updateAttributesUrl: '{{ route('realisationModules.updateAttributes') }}', 
        deleteUrl: '{{ route('realisationModules.destroy',  ['realisationModule' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-realisationModule')),
        calculationUrl:  '{{ route('realisationModules.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::realisationModule.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::realisationModule.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $realisationModule_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="realisationModule-crud" class="crud">
    @section('realisationModule-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::realisationModule.singular");
    @endphp
    <x-crud-header 
        id="realisationModule-crud-header" icon="fas fa-medal"  
        iconColor="text-info"
        title="{{ $realisationModule_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('realisationModule-crud-table')
    <section id="realisationModule-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('realisationModule-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$realisationModules_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$realisationModule_instance"
                                    :createPermission="'create-realisationModule'"
                                    :createRoute="route('realisationModules.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-realisationModule'"
                                    :importRoute="route('realisationModules.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-realisationModule'"
                                    :exportXlsxRoute="route('realisationModules.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('realisationModules.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$realisationModule_viewTypes"
                                    :viewType="$realisationModule_viewType"
                                    :total="$realisationModules_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('realisationModule-crud-filters')
                @if(!empty($realisationModules_total) &&  $realisationModules_total > 5)
                <div class="card-header">
                    <form id="realisationModule-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($realisationModules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($realisationModules_filters as $filter)
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
                        @section('realisationModule-crud-search-bar')
                        <div id="realisationModule-crud-search-bar"
                            class="{{ count($realisationModules_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('realisationModules_search')"
                                name="realisationModules_search"
                                id="realisationModules_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="realisationModule-data-container" class="data-container">
                    @if($realisationModule_viewType != "widgets")
                    @include("PkgApprentissage::realisationModule._$realisationModule_viewType")
                    @endif
                </div>
                @section('realisationModule-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-realisationModule")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('realisationModules.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-realisationModule')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('realisationModules.bulkDelete') }}" 
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
     <section id="realisationModule-data-container-out" >
        @if($realisationModule_viewType == "widgets")
        @include("PkgApprentissage::realisationModule._$realisationModule_viewType")
        @endif
    </section>
    @show
</div>