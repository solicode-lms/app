{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'competence',
        filterFormSelector: '#competence-crud-filter-form',
        crudSelector: '#competence-crud',
        tableSelector: '#competence-data-container',
        formSelector: '#competenceForm',
        modalSelector : '#competenceModal',
        indexUrl: '{{ route('competences.index') }}', 
        createUrl: '{{ route('competences.create') }}',
        editUrl: '{{ route('competences.edit',  ['competence' => ':id']) }}',
        showUrl: '{{ route('competences.show',  ['competence' => ':id']) }}',
        storeUrl: '{{ route('competences.store') }}', 
        deleteUrl: '{{ route('competences.destroy',  ['competence' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgCompetences::competence.singular") }}',
    });
</script>
@endpush
<div id="competence-crud" class="crud">
    @section('competence-crud-header')
    @php
        $package = __("PkgCompetences::PkgCompetences.name");
       $titre = __("PkgCompetences::competence.singular");
    @endphp
    <x-crud-header 
        id="competence-crud-header" icon="fas fa-tools"  
        iconColor="text-info"
        title="{{ __('PkgCompetences::competence.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('competence-crud-table')
    <section id="competence-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('competence-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$competences_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-competence'"
                            :createRoute="route('competences.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-competence'"
                            :importRoute="route('competences.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-competence'"
                            :exportRoute="route('competences.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('competence-crud-filters')
                <div class="card-header">
                    <form id="competence-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($modules_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($competences_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('competence-crud-search-bar')
                        <div id="competence-crud-search-bar"
                            class="{{ count($competences_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('competences_search')"
                                name="competences_search"
                                id="competences_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="competence-data-container" class="data-container">
                    @include('PkgCompetences::competence._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('competence-crud-modal')
    <x-modal id="competenceModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>