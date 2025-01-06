{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'resource',
        filterFormSelector: '#resource-crud-filter-form',
        crudSelector: '#resource-crud',
        tableSelector: '#resource-data-container',
        formSelector: '#resourceForm',
        modalSelector : '#resourceModal',
        indexUrl: '{{ route('resources.index') }}', 
        createUrl: '{{ route('resources.create') }}',
        editUrl: '{{ route('resources.edit',  ['resource' => ':id']) }}',
        showUrl: '{{ route('resources.show',  ['resource' => ':id']) }}',
        storeUrl: '{{ route('resources.store') }}', 
        deleteUrl: '{{ route('resources.destroy',  ['resource' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::resource.singular") }}',
    });
</script>
@endpush
<div id="resource-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::resource.singular");
    @endphp
    <x-crud-header 
        id="resource-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::resource.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="resource-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$resources_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-resource'"
                            :createRoute="route('resources.create')"
                            :createText="__('Ajouter une resource')"
                            :importPermission="'import-resource'"
                            :importRoute="route('resources.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-resource'"
                            :exportRoute="route('resources.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="resource-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($resources_filters as $filter)
                                <x-filter-field 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="resource-crud-search-bar"
                            class="{{ count($resources_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('resources_search')"
                                name="resources_search"
                                id="resources_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="resource-data-container" class="data-container">
                    @include('PkgCreationProjet::resource._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="resourceModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>