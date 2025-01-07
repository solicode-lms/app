{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'appreciation',
        filterFormSelector: '#appreciation-crud-filter-form',
        crudSelector: '#appreciation-crud',
        tableSelector: '#appreciation-data-container',
        formSelector: '#appreciationForm',
        modalSelector : '#appreciationModal',
        indexUrl: '{{ route('appreciations.index') }}', 
        createUrl: '{{ route('appreciations.create') }}',
        editUrl: '{{ route('appreciations.edit',  ['appreciation' => ':id']) }}',
        showUrl: '{{ route('appreciations.show',  ['appreciation' => ':id']) }}',
        storeUrl: '{{ route('appreciations.store') }}', 
        deleteUrl: '{{ route('appreciations.destroy',  ['appreciation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::appreciation.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::appreciation.singular") }}',
    });
</script>
@endpush
<div id="appreciation-crud" class="crud">
    @section('crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::appreciation.singular");
    @endphp
    <x-crud-header 
        id="appreciation-crud-header" icon="fas fa-chart-line"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::appreciation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('crud-table')
    <section id="appreciation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$appreciations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-appreciation'"
                            :createRoute="route('appreciations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-appreciation'"
                            :importRoute="route('appreciations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-appreciation'"
                            :exportRoute="route('appreciations.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('crud-filters')
                <div class="card-header">
                    <form id="appreciation-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($appreciations_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('crud-search-bar')
                        <div id="appreciation-crud-search-bar"
                            class="{{ count($appreciations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('appreciations_search')"
                                name="appreciations_search"
                                id="appreciations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="appreciation-data-container" class="data-container">
                    @include('PkgCompetences::appreciation._table')
                </div>
            </div>
        </div>
    </div>
    </section>
    @show
    @section('crud-modal')
    <x-modal id="appreciationModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>