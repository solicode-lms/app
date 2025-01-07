{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'technology',
        filterFormSelector: '#technology-crud-filter-form',
        crudSelector: '#technology-crud',
        tableSelector: '#technology-data-container',
        formSelector: '#technologyForm',
        modalSelector : '#technologyModal',
        indexUrl: '{{ route('technologies.index') }}', 
        createUrl: '{{ route('technologies.create') }}',
        editUrl: '{{ route('technologies.edit',  ['technology' => ':id']) }}',
        showUrl: '{{ route('technologies.show',  ['technology' => ':id']) }}',
        storeUrl: '{{ route('technologies.store') }}', 
        deleteUrl: '{{ route('technologies.destroy',  ['technology' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::technology.singular") }}',
    });
</script>
@endpush
<div id="technology-crud" class="crud">
    @section('technology-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::technology.singular");
    @endphp
    <x-crud-header 
        id="technology-crud-header" icon="fas fa-bolt"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::technology.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('technology-crud-table')
    <section id="technology-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('technology-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$technologies_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-technology'"
                            :createRoute="route('technologies.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-technology'"
                            :importRoute="route('technologies.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-technology'"
                            :exportRoute="route('technologies.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('technology-crud-filters')
                <div class="card-header">
                    <form id="technology-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($technologies_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('technology-crud-search-bar')
                        <div id="technology-crud-search-bar"
                            class="{{ count($technologies_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('technologies_search')"
                                name="technologies_search"
                                id="technologies_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="technology-data-container" class="data-container">
                    @include('PkgCompetences::technology._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('technology-crud-modal')
    <x-modal id="technologyModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>