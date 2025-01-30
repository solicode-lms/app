{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        entity_name: 'livrablesRealisation',
        filterFormSelector: '#livrablesRealisation-crud-filter-form',
        crudSelector: '#livrablesRealisation-crud',
        tableSelector: '#livrablesRealisation-data-container',
        formSelector: '#livrablesRealisationForm',
        modalSelector : '#livrablesRealisationModal',
        indexUrl: '{{ route('livrablesRealisations.index') }}', 
        createUrl: '{{ route('livrablesRealisations.create') }}',
        editUrl: '{{ route('livrablesRealisations.edit',  ['livrablesRealisation' => ':id']) }}',
        showUrl: '{{ route('livrablesRealisations.show',  ['livrablesRealisation' => ':id']) }}',
        storeUrl: '{{ route('livrablesRealisations.store') }}', 
        deleteUrl: '{{ route('livrablesRealisations.destroy',  ['livrablesRealisation' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::livrablesRealisation.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::livrablesRealisation.singular") }}',
    });
</script>
@endpush
<div id="livrablesRealisation-crud" class="crud">
    @section('livrablesRealisation-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::livrablesRealisation.singular");
    @endphp
    <x-crud-header 
        id="livrablesRealisation-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgRealisationProjets::livrablesRealisation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('livrablesRealisation-crud-table')
    <section id="livrablesRealisation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('livrablesRealisation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$livrablesRealisations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-livrablesRealisation'"
                            :createRoute="route('livrablesRealisations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-livrablesRealisation'"
                            :importRoute="route('livrablesRealisations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-livrablesRealisation'"
                            :exportRoute="route('livrablesRealisations.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('livrablesRealisation-crud-filters')
                <div class="card-header">
                    <form id="livrablesRealisation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($livrablesRealisations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($livrablesRealisations_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('livrablesRealisation-crud-search-bar')
                        <div id="livrablesRealisation-crud-search-bar"
                            class="{{ count($livrablesRealisations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('livrablesRealisations_search')"
                                name="livrablesRealisations_search"
                                id="livrablesRealisations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="livrablesRealisation-data-container" class="data-container">
                    @include('PkgRealisationProjets::livrablesRealisation._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('livrablesRealisation-crud-modal')
    <x-modal id="livrablesRealisationModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>