{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: {{ isset($edit_has_many) && $edit_has_many ? 'true' : 'false' }},
        entity_name: 'formateur',
        filterFormSelector: '#formateur-crud-filter-form',
        crudSelector: '#formateur-crud',
        tableSelector: '#formateur-data-container',
        formSelector: '#formateurForm',
        modalSelector : '#formateurModal',
        indexUrl: '{{ route('formateurs.index') }}', 
        createUrl: '{{ route('formateurs.create') }}',
        editUrl: '{{ route('formateurs.edit',  ['formateur' => ':id']) }}',
        showUrl: '{{ route('formateurs.show',  ['formateur' => ':id']) }}',
        storeUrl: '{{ route('formateurs.store') }}', 
        deleteUrl: '{{ route('formateurs.destroy',  ['formateur' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::formateur.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgUtilisateurs::formateur.singular") }}',
    });
</script>
@endpush
<div id="formateur-crud" class="crud">
    @section('formateur-crud-header')
    @php
        $package = __("PkgUtilisateurs::PkgUtilisateurs.name");
       $titre = __("PkgUtilisateurs::formateur.singular");
    @endphp

    <x-crud-header 
        id="formateur-crud-header" icon="fas fa-chalkboard-teacher"  
        iconColor="text-info"
        title="{{ __('PkgUtilisateurs::formateur.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />

    @show
    @section('formateur-crud-table')
    <section id="formateur-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('formateur-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$formateurs_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-formateur'"
                            :createRoute="route('formateurs.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-formateur'"
                            :importRoute="route('formateurs.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-formateur'"
                            :exportRoute="route('formateurs.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('formateur-crud-filters')
                <div class="card-header">
                    <form id="formateur-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($formateurs_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($formateurs_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('formateur-crud-search-bar')
                        <div id="formateur-crud-search-bar"
                            class="{{ count($formateurs_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('formateurs_search')"
                                name="formateurs_search"
                                id="formateurs_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="formateur-data-container" class="data-container">
                    @include('PkgUtilisateurs::formateur._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('formateur-crud-modal')
    <x-modal id="formateurModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>