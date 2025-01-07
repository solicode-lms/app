{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@push('scripts')
<script>
    window.entitiesConfig = window.entitiesConfig || [];
    window.entitiesConfig.push({
        edit_has_many: false,
        entity_name: 'widget',
        filterFormSelector: '#widget-crud-filter-form',
        crudSelector: '#widget-crud',
        tableSelector: '#widget-data-container',
        formSelector: '#widgetForm',
        modalSelector : '#widgetModal',
        indexUrl: '{{ route('widgets.index') }}', 
        createUrl: '{{ route('widgets.create') }}',
        editUrl: '{{ route('widgets.edit',  ['widget' => ':id']) }}',
        showUrl: '{{ route('widgets.show',  ['widget' => ':id']) }}',
        storeUrl: '{{ route('widgets.store') }}', 
        deleteUrl: '{{ route('widgets.destroy',  ['widget' => ':id']) }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widget.singular") }}',
        edit_title: '{{__("Core::msg.add") . " : " . __("PkgWidgets::widget.singular") }}',
    });
</script>
@endpush
<div id="widget-crud" class="crud">
    @section('widget-crud-header')
    @php
        $package = __("PkgWidgets::PkgWidgets.name");
       $titre = __("PkgWidgets::widget.singular");
    @endphp
    <x-crud-header 
        id="widget-crud-header" icon="fas fa-table"  
        iconColor="text-info"
        title="{{ __('PkgWidgets::widget.plural') }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('widget-crud-table')
    <section id="widget-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('widget-crud-stats-bar')
                <div class="card-header row">
                    <!-- Statistiques et Actions -->
                    <div class="col-sm-9">
                        <x-crud-stats-summary
                            icon="fas fa-chart-bar text-info"
                            :stats="$widgets_stats"
                        />
                    </div>
                    <div class="col-sm-3">
                        <x-crud-actions
                            :createPermission="'create-widget'"
                            :createRoute="route('widgets.create')"
                            :createText="__('Ajouter')"
                            :importPermission="'import-widget'"
                            :importRoute="route('widgets.import')"
                            :importText="__('Importer')"
                            :exportPermission="'export-widget'"
                            :exportRoute="route('widgets.export')"
                            :exportText="__('Exporter')"
                        />
                    </div>
                </div>
                @show
                @section('widget-crud-filters')
                <div class="card-header">
                    <form id="widget-crud-filter-form" method="GET" class="row">
                        <x-filter-group>
                            <!-- Filtres spécifiques -->
                            @foreach ($widgets_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" />
                            @endforeach
                        </x-filter-group>
                        @section('widget-crud-search-bar')
                        <div id="widget-crud-search-bar"
                            class="{{ count($widgets_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('widgets_search')"
                                name="widgets_search"
                                id="widgets_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @show
                <div id="widget-data-container" class="data-container">
                    @include('PkgWidgets::widget._table')
                </div>
            </div>
        </div>
    </section>
    @show
    @section('widget-crud-modal')
    <x-modal id="widgetModal" title="Ajouter ou Modifier"></x-modal>
    @show
</div>