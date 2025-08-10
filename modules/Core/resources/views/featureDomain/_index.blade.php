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
        entity_name: 'featureDomain',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'featureDomain.index' }}', 
        filterFormSelector: '#featureDomain-crud-filter-form',
        crudSelector: '#featureDomain-crud',
        tableSelector: '#featureDomain-data-container',
        formSelector: '#featureDomainForm',
        indexUrl: '{{ route('featureDomains.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('featureDomains.create') }}',
        editUrl: '{{ route('featureDomains.edit',  ['featureDomain' => ':id']) }}',
        showUrl: '{{ route('featureDomains.show',  ['featureDomain' => ':id']) }}',
        getEntityUrl: '{{ route("featureDomains.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('featureDomains.store') }}', 
        updateAttributesUrl: '{{ route('featureDomains.updateAttributes') }}', 
        deleteUrl: '{{ route('featureDomains.destroy',  ['featureDomain' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-featureDomain')),
        calculationUrl:  '{{ route('featureDomains.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::featureDomain.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $featureDomain_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="featureDomain-crud" class="crud">
    @section('featureDomain-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::featureDomain.singular");
    @endphp
    <x-crud-header 
        id="featureDomain-crud-header" icon="fas fa-th-large"  
        iconColor="text-info"
        title="{{ $featureDomain_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('featureDomain-crud-table')
    <section id="featureDomain-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('featureDomain-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$featureDomains_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$featureDomain_instance"
                                    :createPermission="'create-featureDomain'"
                                    :createRoute="route('featureDomains.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-featureDomain'"
                                    :importRoute="route('featureDomains.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-featureDomain'"
                                    :exportXlsxRoute="route('featureDomains.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('featureDomains.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$featureDomain_viewTypes"
                                    :viewType="$featureDomain_viewType"
                                    :total="$featureDomains_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('featureDomain-crud-filters')
                @if(!empty($featureDomains_total) &&  $featureDomains_total > 5)
                <div class="card-header">
                    <form id="featureDomain-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($featureDomains_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($featureDomains_filters as $filter)
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
                        @section('featureDomain-crud-search-bar')
                        <div id="featureDomain-crud-search-bar"
                            class="{{ count($featureDomains_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('featureDomains_search')"
                                name="featureDomains_search"
                                id="featureDomains_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="featureDomain-data-container" class="data-container">
                    @if($featureDomain_viewType != "widgets")
                    @include("Core::featureDomain._$featureDomain_viewType")
                    @endif
                </div>
                @section('featureDomain-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-featureDomain")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('featureDomains.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-featureDomain')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('featureDomains.bulkDelete') }}" 
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
     <section id="featureDomain-data-container-out" >
        @if($featureDomain_viewType == "widgets")
        @include("Core::featureDomain._$featureDomain_viewType")
        @endif
    </section>
    @show
</div>