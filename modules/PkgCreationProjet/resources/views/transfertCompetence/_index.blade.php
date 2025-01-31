{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'true' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'transfertCompetence',
        filterFormSelector: '#transfertCompetence-crud-filter-form',
        crudSelector: '#transfertCompetence-crud',
        tableSelector: '#transfertCompetence-data-container',
        formSelector: '#transfertCompetenceForm',
        modalSelector : '#transfertCompetenceModal',
        indexUrl: '{{ route('transfertCompetences.index') }}', 
        createUrl: '{{ route('transfertCompetences.create') }}',
        editUrl: '{{ route('transfertCompetences.edit',  ['transfertCompetence' => ':id']) }}',
        showUrl: '{{ route('transfertCompetences.show',  ['transfertCompetence' => ':id']) }}',
        storeUrl: '{{ route('transfertCompetences.store') }}', 
        deleteUrl: '{{ route('transfertCompetences.destroy',  ['transfertCompetence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCreationProjet::transfertCompetence.singular") }}',
    });
</script>
@endpush
<div id="transfertCompetence-crud" class="crud">
    @section('transfertCompetence-crud-header')
    @php
        $package = __("PkgCreationProjet::PkgCreationProjet.name");
       $titre = __("PkgCreationProjet::transfertCompetence.singular");
    @endphp
    <x-crud-header 
        id="transfertCompetence-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgCreationProjet::transfertCompetence.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('transfertCompetence-crud-table')
    <section id="transfertCompetence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('transfertCompetence-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$transfertCompetences_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-transfertCompetence'"
                            :createRoute="route('transfertCompetences.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-transfertCompetence'"
                            :importRoute="route('transfertCompetences.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-transfertCompetence'"
                            :exportRoute="route('transfertCompetences.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('transfertCompetence-crud-filters')
                <div class="card-header">
                    <form id="transfertCompetence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($transfertCompetences_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($transfertCompetences_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('transfertCompetence-crud-search-bar')
                        <div id="transfertCompetence-crud-search-bar"
                            class="{{ count($transfertCompetences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('transfertCompetences_search')"
                                name="transfertCompetences_search"
                                id="transfertCompetences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="transfertCompetence-data-container" class="data-container">
                    @include('PkgCreationProjet::transfertCompetence._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('transfertCompetence-crud-modal')
    <x-modal id="transfertCompetenceModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>