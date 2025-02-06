{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'feature',
        filterFormSelector: '#feature-crud-filter-form',
        crudSelector: '#feature-crud',
        tableSelector: '#feature-data-container',
        formSelector: '#featureForm',
        indexUrl: '{{ route('features.index') }}', 
        createUrl: '{{ route('features.create') }}',
        editUrl: '{{ route('features.edit',  ['feature' => ':id']) }}',
        showUrl: '{{ route('features.show',  ['feature' => ':id']) }}',
        storeUrl: '{{ route('features.store') }}', 
        deleteUrl: '{{ route('features.destroy',  ['feature' => ':id']) }}', 
        calculationUrl:  '{{ route('features.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::feature.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("Core::feature.singular") }}',
    });
</script>

<div id="feature-crud" class="crud">
    @section('feature-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::feature.singular");
    @endphp
    <x-crud-header 
        id="feature-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('Core::feature.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('feature-crud-table')
    <section id="feature-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('feature-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$features_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-feature'"
                            :createRoute="route('features.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-feature'"
                            :importRoute="route('features.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-feature'"
                            :exportRoute="route('features.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('feature-crud-filters')
                <div class="card-header">
                    <form id="feature-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($features_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($features_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('feature-crud-search-bar')
                        <div id="feature-crud-search-bar"
                            class="{{ count($features_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('features_search')"
                                name="features_search"
                                id="features_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="feature-data-container" class="data-container">
                    @include('Core::feature._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>