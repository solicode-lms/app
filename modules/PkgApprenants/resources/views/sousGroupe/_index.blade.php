{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'sousGroupe',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'sousGroupe.index' }}', 
        filterFormSelector: '#sousGroupe-crud-filter-form',
        crudSelector: '#sousGroupe-crud',
        tableSelector: '#sousGroupe-data-container',
        formSelector: '#sousGroupeForm',
        indexUrl: '{{ route('sousGroupes.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('sousGroupes.create') }}',
        editUrl: '{{ route('sousGroupes.edit',  ['sousGroupe' => ':id']) }}',
        showUrl: '{{ route('sousGroupes.show',  ['sousGroupe' => ':id']) }}',
        storeUrl: '{{ route('sousGroupes.store') }}', 
        updateAttributesUrl: '{{ route('sousGroupes.updateAttributes') }}', 
        deleteUrl: '{{ route('sousGroupes.destroy',  ['sousGroupe' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-sousGroupe')),
        calculationUrl:  '{{ route('sousGroupes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::sousGroupe.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::sousGroupe.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $sousGroupe_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="sousGroupe-crud" class="crud">
    @section('sousGroupe-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::sousGroupe.singular");
    @endphp
    <x-crud-header 
        id="sousGroupe-crud-header" icon="fa-table"  
        iconColor="text-info"
        title="{{ $sousGroupe_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('sousGroupe-crud-table')
    <section id="sousGroupe-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('sousGroupe-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$sousGroupes_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$sousGroupe_instance"
                                    :createPermission="'create-sousGroupe'"
                                    :createRoute="route('sousGroupes.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-sousGroupe'"
                                    :importRoute="route('sousGroupes.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-sousGroupe'"
                                    :exportXlsxRoute="route('sousGroupes.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('sousGroupes.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$sousGroupe_viewTypes"
                                    :viewType="$sousGroupe_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('sousGroupe-crud-filters')
                <div class="card-header">
                    <form id="sousGroupe-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($sousGroupes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($sousGroupes_filters as $filter)
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
                        @section('sousGroupe-crud-search-bar')
                        <div id="sousGroupe-crud-search-bar"
                            class="{{ count($sousGroupes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sousGroupes_search')"
                                name="sousGroupes_search"
                                id="sousGroupes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sousGroupe-data-container" class="data-container">
                    @if($sousGroupe_viewType != "widgets")
                    @include("PkgApprenants::sousGroupe._$sousGroupe_viewType")
                    @endif
                </div>
                @section('sousGroupe-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-sousGroupe")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('sousGroupes.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-sousGroupe')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('sousGroupes.bulkDelete') }}" 
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
     <section id="sousGroupe-data-container-out" >
        @if($sousGroupe_viewType == "widgets")
        @include("PkgApprenants::sousGroupe._$sousGroupe_viewType")
        @endif
    </section>
    @show
</div>