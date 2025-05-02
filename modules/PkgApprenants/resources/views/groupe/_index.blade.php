{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'groupe',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'groupe.index' }}', 
        filterFormSelector: '#groupe-crud-filter-form',
        crudSelector: '#groupe-crud',
        tableSelector: '#groupe-data-container',
        formSelector: '#groupeForm',
        indexUrl: '{{ route('groupes.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('groupes.create') }}',
        editUrl: '{{ route('groupes.edit',  ['groupe' => ':id']) }}',
        showUrl: '{{ route('groupes.show',  ['groupe' => ':id']) }}',
        storeUrl: '{{ route('groupes.store') }}', 
        updateAttributesUrl: '{{ route('groupes.updateAttributes') }}', 
        deleteUrl: '{{ route('groupes.destroy',  ['groupe' => ':id']) }}', 
        calculationUrl:  '{{ route('groupes.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::groupe.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgApprenants::groupe.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $groupe_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="groupe-crud" class="crud">
    @section('groupe-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::groupe.singular");
    @endphp
    <x-crud-header 
        id="groupe-crud-header" icon="fas fa-users"  
        iconColor="text-info"
        title="{{ $groupe_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('groupe-crud-table')
    <section id="groupe-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('groupe-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$groupes_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$groupe_instance"
                                    :createPermission="'create-groupe'"
                                    :createRoute="route('groupes.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-groupe'"
                                    :importRoute="route('groupes.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-groupe'"
                                    :exportXlsxRoute="route('groupes.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('groupes.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$groupe_viewTypes"
                                    :viewType="$groupe_viewType"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('groupe-crud-filters')
                <div class="card-header">
                    <form id="groupe-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($groupes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($groupes_filters as $filter)
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
                        @section('groupe-crud-search-bar')
                        <div id="groupe-crud-search-bar"
                            class="{{ count($groupes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('groupes_search')"
                                name="groupes_search"
                                id="groupes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="groupe-data-container" class="data-container">
                    @if($groupe_viewType == "table")
                    @include("PkgApprenants::groupe._$groupe_viewType")
                    @endif
                </div>
                @section('groupe-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-groupe")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('groupes.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-groupe')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('groupes.bulkDelete') }}" 
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
     <section id="groupe-data-container-out" >
        @if($groupe_viewType == "widgets")
        @include("PkgApprenants::groupe._$groupe_viewType")
        @endif
    </section>
    @show
</div>