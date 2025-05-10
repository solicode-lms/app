{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'filiere',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'filiere.index' }}', 
        filterFormSelector: '#filiere-crud-filter-form',
        crudSelector: '#filiere-crud',
        tableSelector: '#filiere-data-container',
        formSelector: '#filiereForm',
        indexUrl: '{{ route('filieres.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('filieres.create') }}',
        editUrl: '{{ route('filieres.edit',  ['filiere' => ':id']) }}',
        showUrl: '{{ route('filieres.show',  ['filiere' => ':id']) }}',
        storeUrl: '{{ route('filieres.store') }}', 
        updateAttributesUrl: '{{ route('filieres.updateAttributes') }}', 
        deleteUrl: '{{ route('filieres.destroy',  ['filiere' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-filiere')),
        calculationUrl:  '{{ route('filieres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgFormation::filiere.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgFormation::filiere.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $filiere_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="filiere-crud" class="crud">
    @section('filiere-crud-header')
    @php
        $package = __("PkgFormation::PkgFormation.name");
       $titre = __("PkgFormation::filiere.singular");
    @endphp
    <x-crud-header 
        id="filiere-crud-header" icon="fas fa-book"  
        iconColor="text-info"
        title="{{ $filiere_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('filiere-crud-table')
    <section id="filiere-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('filiere-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$filieres_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$filiere_instance"
                                    :createPermission="'create-filiere'"
                                    :createRoute="route('filieres.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-filiere'"
                                    :importRoute="route('filieres.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-filiere'"
                                    :exportXlsxRoute="route('filieres.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('filieres.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$filiere_viewTypes"
                                    :viewType="$filiere_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('filiere-crud-filters')
                <div class="card-header">
                    <form id="filiere-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($filieres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($filieres_filters as $filter)
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
                        @section('filiere-crud-search-bar')
                        <div id="filiere-crud-search-bar"
                            class="{{ count($filieres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('filieres_search')"
                                name="filieres_search"
                                id="filieres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="filiere-data-container" class="data-container">
                    @if($filiere_viewType == "table")
                    @include("PkgFormation::filiere._$filiere_viewType")
                    @endif
                </div>
                @section('filiere-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-filiere")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('filieres.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-filiere')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('filieres.bulkDelete') }}" 
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
     <section id="filiere-data-container-out" >
        @if($filiere_viewType == "widgets")
        @include("PkgFormation::filiere._$filiere_viewType")
        @endif
    </section>
    @show
</div>