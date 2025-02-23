{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        editOnFullScreen : false,
        entity_name: 'validation',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'validation.index' }}', 
        filterFormSelector: '#validation-crud-filter-form',
        crudSelector: '#validation-crud',
        tableSelector: '#validation-data-container',
        formSelector: '#validationForm',
        indexUrl: '{{ route('validations.index') }}', 
        createUrl: '{{ route('validations.create') }}',
        editUrl: '{{ route('validations.edit',  ['validation' => ':id']) }}',
        showUrl: '{{ route('validations.show',  ['validation' => ':id']) }}',
        storeUrl: '{{ route('validations.store') }}', 
        deleteUrl: '{{ route('validations.destroy',  ['validation' => ':id']) }}', 
        calculationUrl:  '{{ route('validations.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgRealisationProjets::validation.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgRealisationProjets::validation.singular") }}',
    });
</script>

<div id="validation-crud" class="crud">
    @section('validation-crud-header')
    @php
        $package = __("PkgRealisationProjets::PkgRealisationProjets.name");
       $titre = __("PkgRealisationProjets::validation.singular");
    @endphp
    <x-crud-header 
        id="validation-crud-header" icon="fas fa-check-circle"  
        iconColor="text-info"
        title="{{ __('PkgRealisationProjets::validation.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('validation-crud-table')
    <section id="validation-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('validation-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$validations_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        @canany(['create-validation','import-validation','export-validation'])
                        <x-crud-actions
                            :createPermission="'create-validation'"
                            :createRoute="route('validations.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-validation'"
                            :importRoute="route('validations.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-validation'"
                            :exportXlsxRoute="route('validations.export', ['format' => 'xlsx'])"
                            :exportCsvRoute="route('validations.export', ['format' => 'csv']) "
                            :exportText="__('Exporter')"
                        />
                        @endcan
                    </div>
                </div>
                @show
                @section('validation-crud-filters')
                <div class="card-header">
                    <form id="validation-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($validations_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($validations_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('validation-crud-search-bar')
                        <div id="validation-crud-search-bar"
                            class="{{ count($validations_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('validations_search')"
                                name="validations_search"
                                id="validations_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="validation-data-container" class="data-container">
                    @include('PkgRealisationProjets::validation._table')
                </div>
            </div>
        </div>
    </section>
    @show
</div>