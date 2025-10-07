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
        entity_name: 'ePackage',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'ePackage.index' }}', 
        filterFormSelector: '#ePackage-crud-filter-form',
        crudSelector: '#ePackage-crud',
        tableSelector: '#ePackage-data-container',
        formSelector: '#ePackageForm',
        indexUrl: '{{ route('ePackages.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('ePackages.create') }}',
        editUrl: '{{ route('ePackages.edit',  ['ePackage' => ':id']) }}',
        fieldMetaUrl: '{{ route('ePackages.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('ePackages.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('ePackages.show',  ['ePackage' => ':id']) }}',
        getEntityUrl: '{{ route("ePackages.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('ePackages.store') }}', 
        updateAttributesUrl: '{{ route('ePackages.updateAttributes') }}', 
        deleteUrl: '{{ route('ePackages.destroy',  ['ePackage' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-ePackage')),
        calculationUrl:  '{{ route('ePackages.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::ePackage.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::ePackage.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $ePackage_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="ePackage-crud" class="crud">
    @section('ePackage-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::ePackage.singular");
    @endphp
    <x-crud-header 
        id="ePackage-crud-header" icon="fas fa-box"  
        iconColor="text-info"
        title="{{ $ePackage_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('ePackage-crud-table')
    <section id="ePackage-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('ePackage-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$ePackages_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$ePackage_instance"
                                    :createPermission="'create-ePackage'"
                                    :createRoute="route('ePackages.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-ePackage'"
                                    :importRoute="route('ePackages.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-ePackage'"
                                    :exportXlsxRoute="route('ePackages.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('ePackages.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$ePackage_viewTypes"
                                    :viewType="$ePackage_viewType"
                                    :total="$ePackages_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('ePackage-crud-filters')
                @if(!empty($ePackages_total) &&  $ePackages_total > 10)
                <div class="card-header">
                    <form id="ePackage-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($ePackages_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($ePackages_filters as $filter)
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
                        @section('ePackage-crud-search-bar')
                        <div id="ePackage-crud-search-bar"
                            class="{{ count($ePackages_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('ePackages_search')"
                                name="ePackages_search"
                                id="ePackages_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="ePackage-data-container" class="data-container">
                    @if($ePackage_viewType != "widgets")
                    @include("PkgGapp::ePackage._$ePackage_viewType")
                    @endif
                </div>
                @section('ePackage-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-ePackage")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('ePackages.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-ePackage')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('ePackages.bulkDelete') }}" 
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
     <section id="ePackage-data-container-out" >
        @if($ePackage_viewType == "widgets")
        @include("PkgGapp::ePackage._$ePackage_viewType")
        @endif
    </section>
    @show
</div>