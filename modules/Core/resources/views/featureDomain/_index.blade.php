{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'featureDomain',
        filterFormSelector: '#featureDomain-crud-filter-form',
        crudSelector: '#featureDomain-crud',
        tableSelector: '#featureDomain-data-container',
        formSelector: '#featureDomainForm',
        modalSelector : '#featureDomainModal',
        indexUrl: '{{ route('featureDomains.index') }}', 
        createUrl: '{{ route('featureDomains.create') }}',
        editUrl: '{{ route('featureDomains.edit',  ['featureDomain' => ':id']) }}',
        showUrl: '{{ route('featureDomains.show',  ['featureDomain' => ':id']) }}',
        storeUrl: '{{ route('featureDomains.store') }}', 
        deleteUrl: '{{ route('featureDomains.destroy',  ['featureDomain' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("Core::featureDomain.singular") }}',
    });
</script>
@endpush
<div id="featureDomain-crud" class="crud">
    @section('featureDomain-crud-header')
    @php
        $package = __("Core::Core.name");
       $titre = __("Core::featureDomain.singular");
    @endphp
    <x-crud-header 
        id="featureDomain-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('Core::featureDomain.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('featureDomain-crud-table')
    <section id="featureDomain-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('featureDomain-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$featureDomains_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-featureDomain'"
                            :createRoute="route('featureDomains.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-featureDomain'"
                            :importRoute="route('featureDomains.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-featureDomain'"
                            :exportRoute="route('featureDomains.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('featureDomain-crud-filters')
                <div class="card-header">
                    <form id="featureDomain-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($featureDomains_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($featureDomains_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('featureDomain-crud-search-bar')
                        <div id="featureDomain-crud-search-bar"
                            class="{{ count($featureDomains_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('featureDomains_search')"
                                name="featureDomains_search"
                                id="featureDomains_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="featureDomain-data-container" class="data-container">
                    @include('Core::featureDomain._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('featureDomain-crud-modal')
    <x-modal id="featureDomainModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>