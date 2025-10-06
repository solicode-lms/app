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
        entity_name: 'feature',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'feature.index' }}', 
        filterFormSelector: '#feature-crud-filter-form',
        crudSelector: '#feature-crud',
        tableSelector: '#feature-data-container',
        formSelector: '#featureForm',
        indexUrl: '{{ route('features.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('features.create') }}',
        editUrl: '{{ route('features.edit',  ['feature' => ':id']) }}',
        fieldMetaUrl: '{{ route('features.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('features.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('features.show',  ['feature' => ':id']) }}',
        getEntityUrl: '{{ route("features.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('features.store') }}', 
        updateAttributesUrl: '{{ route('features.updateAttributes') }}', 
        deleteUrl: '{{ route('features.destroy',  ['feature' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-feature')),
        calculationUrl:  '{{ route('features.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::feature.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::feature.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $feature_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="feature-crud" class="crud">
    @section('feature-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::feature.singular");
    @endphp
    <x-crud-header 
        id="feature-crud-header" icon="fas fa-plug"  
        iconColor="text-info"
        title="{{ $feature_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('feature-crud-table')
    <section id="feature-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('feature-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$features_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$feature_instance"
                                    :createPermission="'create-feature'"
                                    :createRoute="route('features.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-feature'"
                                    :importRoute="route('features.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-feature'"
                                    :exportXlsxRoute="route('features.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('features.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$feature_viewTypes"
                                    :viewType="$feature_viewType"
                                    :total="$features_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('feature-crud-filters')
                @if(!empty($features_total) &&  $features_total > 50)
                <div class="card-header">
                    <form id="feature-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($features_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($features_filters as $filter)
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
                        @section('feature-crud-search-bar')
                        <div id="feature-crud-search-bar"
                            class="{{ count($features_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('features_search')"
                                name="features_search"
                                id="features_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="feature-data-container" class="data-container">
                    @if($feature_viewType != "widgets")
                    @include("Core::feature._$feature_viewType")
                    @endif
                </div>
                @section('feature-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-feature")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('features.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-feature')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('features.bulkDelete') }}" 
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
     <section id="feature-data-container-out" >
        @if($feature_viewType == "widgets")
        @include("Core::feature._$feature_viewType")
        @endif
    </section>
    @show
</div>