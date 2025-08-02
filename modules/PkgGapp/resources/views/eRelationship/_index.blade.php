{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'eRelationship',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'eRelationship.index' }}', 
        filterFormSelector: '#eRelationship-crud-filter-form',
        crudSelector: '#eRelationship-crud',
        tableSelector: '#eRelationship-data-container',
        formSelector: '#eRelationshipForm',
        indexUrl: '{{ route('eRelationships.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('eRelationships.create') }}',
        editUrl: '{{ route('eRelationships.edit',  ['eRelationship' => ':id']) }}',
        showUrl: '{{ route('eRelationships.show',  ['eRelationship' => ':id']) }}',
        storeUrl: '{{ route('eRelationships.store') }}', 
        updateAttributesUrl: '{{ route('eRelationships.updateAttributes') }}', 
        deleteUrl: '{{ route('eRelationships.destroy',  ['eRelationship' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-eRelationship')),
        calculationUrl:  '{{ route('eRelationships.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGapp::eRelationship.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGapp::eRelationship.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $eRelationship_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="eRelationship-crud" class="crud">
    @section('eRelationship-crud-header')
    @php
        $package = __("PkgGapp::PkgGapp.name");
       $titre = __("PkgGapp::eRelationship.singular");
    @endphp
    <x-crud-header 
        id="eRelationship-crud-header" icon="fas fa-directions"  
        iconColor="text-info"
        title="{{ $eRelationship_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('eRelationship-crud-table')
    <section id="eRelationship-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('eRelationship-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$eRelationships_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                </div>
                                <x-crud-actions
                                    :instanceItem="$eRelationship_instance"
                                    :createPermission="'create-eRelationship'"
                                    :createRoute="route('eRelationships.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-eRelationship'"
                                    :importRoute="route('eRelationships.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-eRelationship'"
                                    :exportXlsxRoute="route('eRelationships.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('eRelationships.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$eRelationship_viewTypes"
                                    :viewType="$eRelationship_viewType"
                                    :total="$eRelationships_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('eRelationship-crud-filters')
                @if(!empty($eRelationships_total) &&  $eRelationships_total > 10)
                <div class="card-header">
                    <form id="eRelationship-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($eRelationships_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($eRelationships_filters as $filter)
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
                        @section('eRelationship-crud-search-bar')
                        <div id="eRelationship-crud-search-bar"
                            class="{{ count($eRelationships_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('eRelationships_search')"
                                name="eRelationships_search"
                                id="eRelationships_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="eRelationship-data-container" class="data-container">
                    @if($eRelationship_viewType != "widgets")
                    @include("PkgGapp::eRelationship._$eRelationship_viewType")
                    @endif
                </div>
                @section('eRelationship-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-eRelationship")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('eRelationships.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-eRelationship')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('eRelationships.bulkDelete') }}" 
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
     <section id="eRelationship-data-container-out" >
        @if($eRelationship_viewType == "widgets")
        @include("PkgGapp::eRelationship._$eRelationship_viewType")
        @endif
    </section>
    @show
</div>