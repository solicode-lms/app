{{-- Ce fichier est maintenu par ESSARRAJ historiqueRealisationTache-crud-filter-form : il donne form in form with dans RelaisationTache --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'historiqueRealisationTache',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'historiqueRealisationTache.index' }}', 
        filterFormSelector: '#historiqueRealisationTache-crud-filter-form',
        crudSelector: '#historiqueRealisationTache-crud',
        tableSelector: '#historiqueRealisationTache-data-container',
        formSelector: '#historiqueRealisationTacheForm',
        indexUrl: '{{ route('historiqueRealisationTaches.index') }}', 
        createUrl: '{{ route('historiqueRealisationTaches.create') }}',
        editUrl: '{{ route('historiqueRealisationTaches.edit',  ['historiqueRealisationTache' => ':id']) }}',
        showUrl: '{{ route('historiqueRealisationTaches.show',  ['historiqueRealisationTache' => ':id']) }}',
        storeUrl: '{{ route('historiqueRealisationTaches.store') }}', 
        updateAttributesUrl: '{{ route('historiqueRealisationTaches.updateAttributes') }}', 
        deleteUrl: '{{ route('historiqueRealisationTaches.destroy',  ['historiqueRealisationTache' => ':id']) }}', 
        calculationUrl:  '{{ route('historiqueRealisationTaches.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgGestionTaches::historiqueRealisationTache.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgGestionTaches::historiqueRealisationTache.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $historiqueRealisationTache_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="historiqueRealisationTache-crud" class="crud">
    @section('historiqueRealisationTache-crud-header')
    @php
        $package = __("PkgGestionTaches::PkgGestionTaches.name");
       $titre = __("PkgGestionTaches::historiqueRealisationTache.singular");
    @endphp
    <x-crud-header 
        id="historiqueRealisationTache-crud-header" icon="fas fa-history"  
        iconColor="text-info"
        title="{{ $historiqueRealisationTache_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('historiqueRealisationTache-crud-table')
    <section id="historiqueRealisationTache-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('historiqueRealisationTache-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$historiqueRealisationTaches_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                        
                            <x-crud-actions
                                :instanceItem="$historiqueRealisationTache_instance"
                                :createPermission="'create-historiqueRealisationTache'"
                                :createRoute="route('historiqueRealisationTaches.create')"
                                :createText="__('Ajouter')"
                                :importPermission="'import-historiqueRealisationTache'"
                                :importRoute="route('historiqueRealisationTaches.import')"
                                :importText="__('Importer')"
                                :exportPermission="'export-historiqueRealisationTache'"
                                :exportXlsxRoute="route('historiqueRealisationTaches.export', ['format' => 'xlsx'])"
                                :exportCsvRoute="route('historiqueRealisationTaches.export', ['format' => 'csv']) "
                                :exportText="__('Exporter')"
                                :viewTypes="$historiqueRealisationTache_viewTypes"
                                :viewType="$historiqueRealisationTache_viewType"
                            />
                        
                        </div>
                    </div>
                </div>
                @show
                @section('historiqueRealisationTache-crud-filters')
                <div class="card-header">
                     
                </div>
                @show
                <div id="historiqueRealisationTache-data-container" class="data-container">
                    @if($historiqueRealisationTache_viewType == "table")
                    @include("PkgGestionTaches::historiqueRealisationTache._$historiqueRealisationTache_viewType")
                    @endif
                </div>
                @section('historiqueRealisationTache-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-historiqueRealisationTache")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('historiqueRealisationTaches.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-historiqueRealisationTache')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('historiqueRealisationTaches.bulkDelete') }}" 
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
     <section id="historiqueRealisationTache-data-container-out" >
        @if($historiqueRealisationTache_viewType == "widgets")
        @include("PkgGestionTaches::historiqueRealisationTache._$historiqueRealisationTache_viewType")
        @endif
    </section>
    @show
</div>