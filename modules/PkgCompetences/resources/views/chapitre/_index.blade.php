{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'chapitre',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'chapitre.index' }}', 
        filterFormSelector: '#chapitre-crud-filter-form',
        crudSelector: '#chapitre-crud',
        tableSelector: '#chapitre-data-container',
        formSelector: '#chapitreForm',
        indexUrl: '{{ route('chapitres.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('chapitres.create') }}',
        editUrl: '{{ route('chapitres.edit',  ['chapitre' => ':id']) }}',
        showUrl: '{{ route('chapitres.show',  ['chapitre' => ':id']) }}',
        storeUrl: '{{ route('chapitres.store') }}', 
        updateAttributesUrl: '{{ route('chapitres.updateAttributes') }}', 
        deleteUrl: '{{ route('chapitres.destroy',  ['chapitre' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-chapitre')),
        calculationUrl:  '{{ route('chapitres.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::chapitre.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgCompetences::chapitre.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $chapitre_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="chapitre-crud" class="crud">
    @section('chapitre-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::chapitre.singular");
    @endphp
    <x-crud-header 
        id="chapitre-crud-header" icon="fas fa-chalkboard"  
        iconColor="text-info"
        title="{{ $chapitre_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('chapitre-crud-table')
    <section id="chapitre-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('chapitre-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$chapitres_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$chapitre_instance"
                                    :createPermission="'create-chapitre'"
                                    :createRoute="route('chapitres.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-chapitre'"
                                    :importRoute="route('chapitres.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-chapitre'"
                                    :exportXlsxRoute="route('chapitres.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('chapitres.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$chapitre_viewTypes"
                                    :viewType="$chapitre_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('chapitre-crud-filters')
                <div class="card-header">
                    <form id="chapitre-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($chapitres_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($chapitres_filters as $filter)
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
                        @section('chapitre-crud-search-bar')
                        <div id="chapitre-crud-search-bar"
                            class="{{ count($chapitres_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('chapitres_search')"
                                name="chapitres_search"
                                id="chapitres_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="chapitre-data-container" class="data-container">
                    @if($chapitre_viewType != "widgets")
                    @include("PkgCompetences::chapitre._$chapitre_viewType")
                    @endif
                </div>
                @section('chapitre-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-chapitre")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('chapitres.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-chapitre')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('chapitres.bulkDelete') }}" 
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
     <section id="chapitre-data-container-out" >
        @if($chapitre_viewType == "widgets")
        @include("PkgCompetences::chapitre._$chapitre_viewType")
        @endif
    </section>
    @show
</div>