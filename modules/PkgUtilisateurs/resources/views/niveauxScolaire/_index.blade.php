{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: true,
        entity_name: 'niveauxScolaire',
        filterFormSelector: '#niveauxScolaire-crud-filter-form',
        crudSelector: '#niveauxScolaire-crud',
        tableSelector: '#niveauxScolaire-data-container',
        formSelector: '#niveauxScolaireForm',
        modalSelector : '#niveauxScolaireModal',
        indexUrl: '{{ route('niveauxScolaires.index') }}', 
        createUrl: '{{ route('niveauxScolaires.create') }}',
        editUrl: '{{ route('niveauxScolaires.edit',  ['niveauxScolaire' => ':id']) }}',
        showUrl: '{{ route('niveauxScolaires.show',  ['niveauxScolaire' => ':id']) }}',
        storeUrl: '{{ route('niveauxScolaires.store') }}', 
        deleteUrl: '{{ route('niveauxScolaires.destroy',  ['niveauxScolaire' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::niveauxScolaire.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::niveauxScolaire.singular") }}',
    });
</script>
@endpush
<div id="niveauxScolaire-crud" class="crud">
    @section('niveauxScolaire-crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::niveauxScolaire.singular");
    @endphp
    <x-crud-header 
        id="niveauxScolaire-crud-header" icon="fas fa-graduation-cap"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::niveauxScolaire.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('niveauxScolaire-crud-table')
    <section id="niveauxScolaire-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('niveauxScolaire-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$niveauxScolaires_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-niveauxScolaire'"
                            :createRoute="route('niveauxScolaires.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-niveauxScolaire'"
                            :importRoute="route('niveauxScolaires.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-niveauxScolaire'"
                            :exportRoute="route('niveauxScolaires.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('niveauxScolaire-crud-filters')
                <div class="card-header">
                    <form id="niveauxScolaire-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($niveauxScolaires_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($niveauxScolaires_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('niveauxScolaire-crud-search-bar')
                        <div id="niveauxScolaire-crud-search-bar"
                            class="{{ count($niveauxScolaires_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('niveauxScolaires_search')"
                                name="niveauxScolaires_search"
                                id="niveauxScolaires_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="niveauxScolaire-data-container" class="data-container">
                    @include('PkgUtilisateurs::niveauxScolaire._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('niveauxScolaire-crud-modal')
    <x-modal id="niveauxScolaireModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>