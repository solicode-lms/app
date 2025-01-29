{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'niveauCompetence',
        filterFormSelector: '#niveauCompetence-crud-filter-form',
        crudSelector: '#niveauCompetence-crud',
        tableSelector: '#niveauCompetence-data-container',
        formSelector: '#niveauCompetenceForm',
        modalSelector : '#niveauCompetenceModal',
        indexUrl: '{{ route('niveauCompetences.index') }}', 
        createUrl: '{{ route('niveauCompetences.create') }}',
        editUrl: '{{ route('niveauCompetences.edit',  ['niveauCompetence' => ':id']) }}',
        showUrl: '{{ route('niveauCompetences.show',  ['niveauCompetence' => ':id']) }}',
        storeUrl: '{{ route('niveauCompetences.store') }}', 
        deleteUrl: '{{ route('niveauCompetences.destroy',  ['niveauCompetence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::niveauCompetence.singular") }}',
    });
</script>
@endpush
<div id="niveauCompetence-crud" class="crud">
    @section('niveauCompetence-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::niveauCompetence.singular");
    @endphp
    <x-crud-header 
        id="niveauCompetence-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::niveauCompetence.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauCompetence-crud-table')
    <section id="niveauCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauCompetence-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$niveauCompetences_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-niveauCompetence'"
                            :createRoute="route('niveauCompetences.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-niveauCompetence'"
                            :importRoute="route('niveauCompetences.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-niveauCompetence'"
                            :exportRoute="route('niveauCompetences.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('niveauCompetence-crud-filters')
                <div class="card-header">
                    <form id="niveauCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauCompetences_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('niveauCompetence-crud-search-bar')
                        <div id="niveauCompetence-crud-search-bar"
                            class="{{ count($niveauCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauCompetences_search')"
                                name="niveauCompetences_search"
                                id="niveauCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="niveauCompetence-data-container" class="data-container">
                    @include('PkgCompetences::niveauCompetence._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('niveauCompetence-crud-modal')
    <x-modal id="niveauCompetenceModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>