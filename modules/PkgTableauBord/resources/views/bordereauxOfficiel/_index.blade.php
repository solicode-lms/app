{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'bordereauxOfficiel',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'bordereauxOfficiel.index' }}', 
        filterFormSelector: '#bordereauxOfficiel-crud-filter-form',
        crudSelector: '#bordereauxOfficiel-crud',
        tableSelector: '#bordereauxOfficiel-data-container',
        formSelector: '#bordereauxOfficielForm',
        indexUrl: '{{ route('bordereauxOfficiels.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('bordereauxOfficiels.create') }}',
        editUrl: '{{ route('bordereauxOfficiels.edit',  ['bordereauxOfficiel' => ':id']) }}',
        fieldMetaUrl: '{{ route('bordereauxOfficiels.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('bordereauxOfficiels.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('bordereauxOfficiels.show',  ['bordereauxOfficiel' => ':id']) }}',
        getEntityUrl: '{{ route("bordereauxOfficiels.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('bordereauxOfficiels.store') }}', 
        updateAttributesUrl: '{{ route('bordereauxOfficiels.updateAttributes') }}', 
        deleteUrl: '{{ route('bordereauxOfficiels.destroy',  ['bordereauxOfficiel' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-bordereauxOfficiel')),
        calculationUrl:  '{{ route('bordereauxOfficiels.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgTableauBord::bordereauxOfficiel.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgTableauBord::bordereauxOfficiel.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $bordereauxOfficiel_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="bordereauxOfficiel-crud" class="crud">
    @section('bordereauxOfficiel-crud-header')
    @php
        $package = __("PkgTableauBord::PkgTableauBord.name");
       $titre = __("PkgTableauBord::bordereauxOfficiel.singular");
    @endphp
    <x-crud-header 
        id="bordereauxOfficiel-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ $bordereauxOfficiel_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('bordereauxOfficiel-crud-table')
    <section id="bordereauxOfficiel-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('bordereauxOfficiel-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$bordereauxOfficiels_stats"
                            />
                            <x-crud-context-badges :scopeVariables="$scopeVariables ?? []" />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$bordereauxOfficiel_instance"
                                    :createPermission="'create-bordereauxOfficiel'"
                                    :createRoute="route('bordereauxOfficiels.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-bordereauxOfficiel'"
                                    :importRoute="route('bordereauxOfficiels.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-bordereauxOfficiel'"
                                    :exportXlsxRoute="route('bordereauxOfficiels.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('bordereauxOfficiels.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$bordereauxOfficiel_viewTypes"
                                    :viewType="$bordereauxOfficiel_viewType"
                                    :total="$bordereauxOfficiels_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('bordereauxOfficiel-crud-filters')
                <div class="card-header">
                    <form id="bordereauxOfficiel-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($bordereauxOfficiels_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($bordereauxOfficiels_filters as $filter)
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
                        @section('bordereauxOfficiel-crud-search-bar')
                        <div id="bordereauxOfficiel-crud-search-bar"
                            class="{{ count($bordereauxOfficiels_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('bordereauxOfficiels_search')"
                                name="bordereauxOfficiels_search"
                                id="bordereauxOfficiels_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="bordereauxOfficiel-data-container" class="data-container">
                    @if($bordereauxOfficiel_viewType != "widgets")
                    @include("PkgTableauBord::bordereauxOfficiel._$bordereauxOfficiel_viewType")
                    @endif
                </div>
                @section('bordereauxOfficiel-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-bordereauxOfficiel")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('bordereauxOfficiels.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-bordereauxOfficiel')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('bordereauxOfficiels.bulkDelete') }}" 
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
     <section id="bordereauxOfficiel-data-container-out" >
        @if($bordereauxOfficiel_viewType == "widgets")
        @include("PkgTableauBord::bordereauxOfficiel._$bordereauxOfficiel_viewType")
        @endif
    </section>
    @show
</div>