{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'formation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'formation.index' }}', 
        filterFormSelector: '#formation-crud-filter-form',
        crudSelector: '#formation-crud',
        tableSelector: '#formation-data-container',
        formSelector: '#formationForm',
        indexUrl: '{{ route('formations.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('formations.create') }}',
        editUrl: '{{ route('formations.edit',  ['formation' => ':id']) }}',
        showUrl: '{{ route('formations.show',  ['formation' => ':id']) }}',
        storeUrl: '{{ route('formations.store') }}', 
        updateAttributesUrl: '{{ route('formations.updateAttributes') }}', 
        deleteUrl: '{{ route('formations.destroy',  ['formation' => ':id']) }}', 
        calculationUrl:  '{{ route('formations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgAutoformation::formation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgAutoformation::formation.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $formation_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="formation-crud" class="crud">
    @section('formation-crud-header')
    @php
        $package = __("PkgAutoformation::PkgAutoformation.name");
       $titre = __("PkgAutoformation::formation.singular");
    @endphp
    <x-crud-header 
        id="formation-crud-header" icon="fas fa-chalkboard-teacher"  
        iconColor="text-info"
        title="{{ $formation_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('formation-crud-table')
    <section id="formation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('formation-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$formations_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$formation_instance"
                                    :createPermission="'create-formation'"
                                    :createRoute="route('formations.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-formation'"
                                    :importRoute="route('formations.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-formation'"
                                    :exportXlsxRoute="route('formations.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('formations.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$formation_viewTypes"
                                    :viewType="$formation_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('formation-crud-filters')
                <div class="card-header">
                    <form id="formation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($formations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($formations_filters as $filter)
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
                        @section('formation-crud-search-bar')
                        <div id="formation-crud-search-bar"
                            class="{{ count($formations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('formations_search')"
                                name="formations_search"
                                id="formations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="formation-data-container" class="data-container">
                    @if($formation_viewType == "table")
                    @include("PkgAutoformation::formation._$formation_viewType")
                    @endif
                </div>
                @section('formation-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-formation")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('formations.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-formation')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('formations.bulkDelete') }}" 
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
     <section id="formation-data-container-out" >
        @if($formation_viewType == "widgets")
        @include("PkgAutoformation::formation._$formation_viewType")
        @endif
    </section>
    @show
</div>