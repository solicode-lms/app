{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'sysModule',
        filterFormSelector: '#sysModule-crud-filter-form',
        crudSelector: '#sysModule-crud',
        tableSelector: '#sysModule-data-container',
        formSelector: '#sysModuleForm',
        modalSelector : '#sysModuleModal',
        indexUrl: '{{ route('sysModules.index') }}', 
        createUrl: '{{ route('sysModules.create') }}',
        editUrl: '{{ route('sysModules.edit',  ['sysModule' => ':id']) }}',
        showUrl: '{{ route('sysModules.show',  ['sysModule' => ':id']) }}',
        storeUrl: '{{ route('sysModules.store') }}', 
        deleteUrl: '{{ route('sysModules.destroy',  ['sysModule' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::sysModule.singular") }}',
    });
</script>
@endpush
<div id="sysModule-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::sysModule.singular");
    @endphp
    <x-crud-header 
        id="sysModule-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('Core::sysModule.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="sysModule-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$sysModules_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-sysModule'"
                            :createRoute="route('sysModules.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-sysModule'"
                            :importRoute="route('sysModules.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-sysModule'"
                            :exportRoute="route('sysModules.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="sysModule-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($sysModules_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="sysModule-crud-search-bar"
                            class="{{ count($sysModules_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('sysModules_search')"
                                name="sysModules_search"
                                id="sysModules_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="sysModule-data-container" class="data-container">
                    @include('Core::sysModule._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="sysModuleModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>