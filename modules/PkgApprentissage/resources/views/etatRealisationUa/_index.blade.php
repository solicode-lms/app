{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'etatRealisationUa',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'etatRealisationUa.index' }}', 
        filterFormSelector: '#etatRealisationUa-crud-filter-form',
        crudSelector: '#etatRealisationUa-crud',
        tableSelector: '#etatRealisationUa-data-container',
        formSelector: '#etatRealisationUaForm',
        indexUrl: '{{ route('etatRealisationUas.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('etatRealisationUas.create') }}',
        editUrl: '{{ route('etatRealisationUas.edit',  ['etatRealisationUa' => ':id']) }}',
        showUrl: '{{ route('etatRealisationUas.show',  ['etatRealisationUa' => ':id']) }}',
        getEntityUrl: '{{ route("etatRealisationUas.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('etatRealisationUas.store') }}', 
        updateAttributesUrl: '{{ route('etatRealisationUas.updateAttributes') }}', 
        deleteUrl: '{{ route('etatRealisationUas.destroy',  ['etatRealisationUa' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-etatRealisationUa')),
        calculationUrl:  '{{ route('etatRealisationUas.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprentissage::etatRealisationUa.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprentissage::etatRealisationUa.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $etatRealisationUa_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="etatRealisationUa-crud" class="crud">
    @section('etatRealisationUa-crud-header')
    @php
        $package = __("PkgApprentissage::PkgApprentissage.name");
       $titre = __("PkgApprentissage::etatRealisationUa.singular");
    @endphp
    <x-crud-header 
        id="etatRealisationUa-crud-header" icon="fas fa-check-square"  
        iconColor="text-info"
        title="{{ $etatRealisationUa_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('etatRealisationUa-crud-table')
    <section id="etatRealisationUa-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('etatRealisationUa-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$etatRealisationUas_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$etatRealisationUa_instance"
                                    :createPermission="'create-etatRealisationUa'"
                                    :createRoute="route('etatRealisationUas.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-etatRealisationUa'"
                                    :importRoute="route('etatRealisationUas.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-etatRealisationUa'"
                                    :exportXlsxRoute="route('etatRealisationUas.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('etatRealisationUas.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$etatRealisationUa_viewTypes"
                                    :viewType="$etatRealisationUa_viewType"
                                    :total="$etatRealisationUas_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('etatRealisationUa-crud-filters')
                @if(!empty($etatRealisationUas_total) &&  $etatRealisationUas_total > 5)
                <div class="card-header">
                    <form id="etatRealisationUa-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($etatRealisationUas_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($etatRealisationUas_filters as $filter)
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
                        @section('etatRealisationUa-crud-search-bar')
                        <div id="etatRealisationUa-crud-search-bar"
                            class="{{ count($etatRealisationUas_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('etatRealisationUas_search')"
                                name="etatRealisationUas_search"
                                id="etatRealisationUas_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="etatRealisationUa-data-container" class="data-container">
                    @if($etatRealisationUa_viewType != "widgets")
                    @include("PkgApprentissage::etatRealisationUa._$etatRealisationUa_viewType")
                    @endif
                </div>
                @section('etatRealisationUa-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-etatRealisationUa")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('etatRealisationUas.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-etatRealisationUa')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('etatRealisationUas.bulkDelete') }}" 
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
     <section id="etatRealisationUa-data-container-out" >
        @if($etatRealisationUa_viewType == "widgets")
        @include("PkgApprentissage::etatRealisationUa._$etatRealisationUa_viewType")
        @endif
    </section>
    @show
</div>