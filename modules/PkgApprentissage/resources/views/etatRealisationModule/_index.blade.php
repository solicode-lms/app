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
        entity_name: 'etatRealisationModule',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationModule.index' }}', 
        filterFormSelector: '#etatRealisationModule-crud-filter-form',
        crudSelector: '#etatRealisationModule-crud',
        tableSelector: '#etatRealisationModule-data-container',
        formSelector: '#etatRealisationModuleForm',
        indexUrl: '{{ route('etatRealisationModules.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatRealisationModules.create') }}',
        editUrl: '{{ route('etatRealisationModules.edit',  ['etatRealisationModule' => ':id']) }}',
        fieldMetaUrl: '{{ route('etatRealisationModules.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('etatRealisationModules.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('etatRealisationModules.show',  ['etatRealisationModule' => ':id']) }}',
        getEntityUrl: '{{ route("etatRealisationModules.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatRealisationModules.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationModules.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationModules.destroy',  ['etatRealisationModule' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatRealisationModule')),
        calculationUrl:  '{{ route('etatRealisationModules.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::etatRealisationModule.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationModule.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationModule_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationModule-crud" class="crud">
    @section('etatRealisationModule-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::etatRealisationModule.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationModule-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $etatRealisationModule_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationModule-crud-table')
    <section id="etatRealisationModule-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationModule-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationModules_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatRealisationModule_instance"
                                    :createPermission="'create-etatRealisationModule'"
                                    :createRoute="route('etatRealisationModules.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatRealisationModule'"
                                    :importRoute="route('etatRealisationModules.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatRealisationModule'"
                                    :exportXlsxRoute="route('etatRealisationModules.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatRealisationModules.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatRealisationModule_viewTypes"
                                    :viewType="$etatRealisationModule_viewType"
                                    :total="$etatRealisationModules_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationModule-crud-filters')
                @if(!empty($etatRealisationModules_total) &&  $etatRealisationModules_total > 10)
                <div class="card-header">
                    <form id="etatRealisationModule-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationModules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationModules_filters as $filter)
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
                        @section('etatRealisationModule-crud-search-bar')
                        <div id="etatRealisationModule-crud-search-bar"
                            class="{{ count($etatRealisationModules_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationModules_search')"
                                name="etatRealisationModules_search"
                                id="etatRealisationModules_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatRealisationModule-data-container" class="data-container">
                    @if($etatRealisationModule_viewType != "widgets")
                    @include("PkgApprentissage::etatRealisationModule._$etatRealisationModule_viewType")
                    @endif
                </div>
                @section('etatRealisationModule-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationModule")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationModules.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationModule')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationModules.bulkDelete') }}" 
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
     <section id="etatRealisationModule-data-container-out" >
        @if($etatRealisationModule_viewType == "widgets")
        @include("PkgApprentissage::etatRealisationModule._$etatRealisationModule_viewType")
        @endif
    </section>
    @show
</div>