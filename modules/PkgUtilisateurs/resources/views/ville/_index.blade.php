{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'ville',
        filterFormSelector: '#ville-crud-filter-form',
        crudSelector: '#ville-crud',
        tableSelector: '#ville-data-container',
        formSelector: '#villeForm',
        modalSelector : '#villeModal',
        indexUrl: '{{ route('villes.index') }}', 
        createUrl: '{{ route('villes.create') }}',
        editUrl: '{{ route('villes.edit',  ['ville' => ':id']) }}',
        showUrl: '{{ route('villes.show',  ['ville' => ':id']) }}',
        storeUrl: '{{ route('villes.store') }}', 
        deleteUrl: '{{ route('villes.destroy',  ['ville' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::ville.singular") }}',
    });
</script>
@endpush
<div id="ville-crud" class="crud">
    @section('ville-crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::ville.singular");
    @endphp
    <x-crud-header 
        id="ville-crud-header" icon="fas fa-city"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::ville.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('ville-crud-table')
    <section id="ville-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('ville-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$villes_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-ville'"
                            :createRoute="route('villes.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-ville'"
                            :importRoute="route('villes.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-ville'"
                            :exportRoute="route('villes.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('ville-crud-filters')
                <div class="card-header">
                    <form id="ville-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($villes_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($villes_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('ville-crud-search-bar')
                        <div id="ville-crud-search-bar"
                            class="{{ count($villes_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('villes_search')"
                                name="villes_search"
                                id="villes_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="ville-data-container" class="data-container">
                    @include('PkgUtilisateurs::ville._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('ville-crud-modal')
    <x-modal id="villeModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>