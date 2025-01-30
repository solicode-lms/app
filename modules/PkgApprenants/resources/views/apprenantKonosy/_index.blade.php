{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'apprenantKonosy',
        filterFormSelector: '#apprenantKonosy-crud-filter-form',
        crudSelector: '#apprenantKonosy-crud',
        tableSelector: '#apprenantKonosy-data-container',
        formSelector: '#apprenantKonosyForm',
        modalSelector : '#apprenantKonosyModal',
        indexUrl: '{{ route('apprenantKonosies.index') }}', 
        createUrl: '{{ route('apprenantKonosies.create') }}',
        editUrl: '{{ route('apprenantKonosies.edit',  ['apprenantKonosy' => ':id']) }}',
        showUrl: '{{ route('apprenantKonosies.show',  ['apprenantKonosy' => ':id']) }}',
        storeUrl: '{{ route('apprenantKonosies.store') }}', 
        deleteUrl: '{{ route('apprenantKonosies.destroy',  ['apprenantKonosy' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::apprenantKonosy.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgApprenants::apprenantKonosy.singular") }}',
    });
</script>
@endpush
<div id="apprenantKonosy-crud" class="crud">
    @section('apprenantKonosy-crud-header')
    @php
        $package = __("PkgApprenants::PkgApprenants.name");
       $titre = __("PkgApprenants::apprenantKonosy.singular");
    @endphp
    <x-crud-header 
        id="apprenantKonosy-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgApprenants::apprenantKonosy.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('apprenantKonosy-crud-table')
    <section id="apprenantKonosy-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('apprenantKonosy-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$apprenantKonosies_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-apprenantKonosy'"
                            :createRoute="route('apprenantKonosies.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-apprenantKonosy'"
                            :importRoute="route('apprenantKonosies.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-apprenantKonosy'"
                            :exportRoute="route('apprenantKonosies.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('apprenantKonosy-crud-filters')
                <div class="card-header">
                    <form id="apprenantKonosy-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($apprenantKonosies_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($apprenantKonosies_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('apprenantKonosy-crud-search-bar')
                        <div id="apprenantKonosy-crud-search-bar"
                            class="{{ count($apprenantKonosies_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('apprenantKonosies_search')"
                                name="apprenantKonosies_search"
                                id="apprenantKonosies_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="apprenantKonosy-data-container" class="data-container">
                    @include('PkgApprenants::apprenantKonosy._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('apprenantKonosy-crud-modal')
    <x-modal id="apprenantKonosyModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>