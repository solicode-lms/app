{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}


<script>
    window.crudModalManagersConfig = window.crudModalManagersConfig || [];
    window.crudModalManagersConfig.push({
        edit_has_many: {{ !isset($edit_has_many)? 'false' :  ($edit_has_many ? "true": "false") }},
        afterCreateAction: '{{ !isset($afterCreateAction)? '' :  $afterCreateAction }}',
        isMany: {{ isset($isMany) && $isMany ? 'true' : 'false' }},
        data_calcul : {{ isset($data_calcul) && $data_calcul ? 'true' : 'false' }},
        parent_manager_id: {!! isset($parent_manager_id) ? "'$parent_manager_id'" : 'null' !!},
        editOnFullScreen : false,
        entity_name: 'notification',
        contextKey: '{{ isset($contextKey) ? $contextKey : 'notification.index' }}', 
        filterFormSelector: '#notification-crud-filter-form',
        crudSelector: '#notification-crud',
        tableSelector: '#notification-data-container',
        formSelector: '#notificationForm',
        indexUrl: '{{ route('notifications.index') }}', 
        getUserNotificationsUrl: '{{route('notifications.getUserNotifications')}}',
        createUrl: '{{ route('notifications.create') }}',
        editUrl: '{{ route('notifications.edit',  ['notification' => ':id']) }}',
        fieldMetaUrl: '{{ route('notifications.field.meta',  ['id' => ':id', 'field' => ':field']) }}',
        patchInlineUrl: '{{ route('notifications.patchInline',  ['id' => ':id']) }}',
        showUrl: '{{ route('notifications.show',  ['notification' => ':id']) }}',
        getEntityUrl: '{{ route("notifications.getById", ["id" => ":id"]) }}',
        storeUrl: '{{ route('notifications.store') }}', 
        updateAttributesUrl: '{{ route('notifications.updateAttributes') }}', 
        deleteUrl: '{{ route('notifications.destroy',  ['notification' => ':id']) }}', 
        canEdit: @json(Auth::user()->can('edit-notification')),
        calculationUrl:  '{{ route('notifications.dataCalcul') }}', 
        csrfToken: '{{ csrf_token() }}', // Jeton CSRF pour Laravel
        create_title: '{{__("Core::msg.add") . " : " . __("PkgNotification::notification.singular") }}',
        edit_title: '{{__("Core::msg.edit") . " : " . __("PkgNotification::notification.singular") }}',
    });
</script>
<script>
    if(!{{ isset($isMany) && $isMany ? 'true' : 'false' }}){
        window.modalTitle = '{{ $notification_title }}'
    }
    window.contextState = @json($contextState);
    window.sessionState = @json($sessionState);
    window.viewState = @json($viewState);
</script>
<div id="notification-crud" class="crud">
    @section('notification-crud-header')
    @php
        $package = __("PkgNotification::PkgNotification.name");
       $titre = __("PkgNotification::notification.singular");
    @endphp
    <x-crud-header 
        id="notification-crud-header" icon="fas fa-bell"  
        iconColor="text-info"
        title="{{ $notification_title }}"
        :breadcrumbs="[
            ['label' => $package, 'url' => '#'],
            ['label' => $titre]
        ]"
    />
    @show
    @section('notification-crud-table')
    <section id="notification-crud-table" class="content crud-table">
        <div class="container-fluid">
            <div class="card card-outline card-info " id="card_crud">
                @section('notification-crud-stats-bar')
                <div class="card-header">
                    <div class="row">
                        <!-- Statistiques et Actions -->
                        <div class="col-sm-8">
                            <x-crud-stats-summary
                                icon="fas fa-chart-bar text-info"
                                :stats="$notifications_stats"
                            />
                        </div>
                        <div class="col-sm-4">
                            <div class="d-flex align-items-center justify-content-end">
                        
                                <div class="actions d-flex align-items-center crud-action">
                                    @can("index-notification")
                                    <a href="{{ route('notifications.markAllAsRead') }}" data-toggle="tooltip" title="Tout marquer comme lu" class="btn btn-sm btn-outline-info context-state  mr-2 ">
                                        <i class="fas fa-check-double"></i>
                                    </a>
                                    @endcan
                                </div>
                                <x-crud-actions
                                    :instanceItem="$notification_instance"
                                    :createPermission="'create-notification'"
                                    :createRoute="route('notifications.create')"
                                    :createText="__('Ajouter')"
                                    :importPermission="'import-notification'"
                                    :importRoute="route('notifications.import')"
                                    :importText="__('Importer')"
                                    :exportPermission="'export-notification'"
                                    :exportXlsxRoute="route('notifications.export', ['format' => 'xlsx'])"
                                    :exportCsvRoute="route('notifications.export', ['format' => 'csv']) "
                                    :exportText="__('Exporter')"
                                    :viewTypes="$notification_viewTypes"
                                    :viewType="$notification_viewType"
                                    :total="$notifications_total"
                                />
                            </div>


                        
                        </div>
                    </div>
                </div>
                @show
                @section('notification-crud-filters')
                @if(!empty($notifications_total) &&  $notifications_total > 5)
                <div class="card-header">
                    <form id="notification-crud-filter-form" method="GET" class="row">
                        <x-filter-group count="{{count($notifications_filters ?? [])}}">
                            <!-- Filtres spécifiques -->
                            @foreach ($notifications_filters as $filter)
                                <x-filter-field 
                                    :label="$filter['label']" 
                                    :type="$filter['type']" 
                                    :field="$filter['field']" 
                                    :options="$filter['options'] ?? []"
                                    :placeholder="ucfirst(str_replace('_', ' ', $filter['field']))" 
                                    :targetDynamicDropdown="isset($filter['targetDynamicDropdown']) ? $filter['targetDynamicDropdown'] : null"
                                    :targetDynamicDropdownApiUrl="isset($filter['targetDynamicDropdownApiUrl']) ? $filter['targetDynamicDropdownApiUrl'] : null" 
                                    :targetDynamicDropdownFilter="isset($filter['targetDynamicDropdownFilter']) ? $filter['targetDynamicDropdownFilter'] : null" />
                            @endforeach
                        </x-filter-group>
                        @section('notification-crud-search-bar')
                        <div id="notification-crud-search-bar"
                            class="{{ count($notifications_filters) > 0 ? 'col-md-2' : 'col-md-6 mx-auto' }} text-md-right text-left">
                            <x-search-bar
                                :search="request('notifications_search')"
                                name="notifications_search"
                                id="notifications_search"
                                placeholder="Recherche ..."
                            />
                        </div>
                        @show
                    </form>
                </div>
                @endif
                @show
                <div id="notification-data-container" class="data-container">
                    @if($notification_viewType != "widgets")
                    @include("PkgNotification::notification._$notification_viewType")
                    @endif
                </div>
                @section('notification-crud-bulk-actions')
                <div class="crud-bulk-action d-none align-items-center justify-content-between">
                    <span class="bulk-selected-count-container">
                        <strong><span class="bulk-selected-count">0</span> {{ __('élément(s) sélectionné(s)') }}</strong>
                    </span>
                    <span>
                    @can("edit-notification")
                    <button 
                        class="btn btn-sm btn-info bulkActionButton" 
                        data-action-type="modal"
                        data-url="{{ route('notifications.bulkEdit') }}" 
                        data-method="GET">
                        <i class="fas fa-edit"></i> {{ __('Modifier') }}
                    </button>
                    @endcan
                    @can('destroy-notification')
                    <button 
                    class="btn btn-sm btn-outline-danger bulkActionButton" 
                    data-url="{{ route('notifications.bulkDelete') }}" 
                    data-method="POST" 
                    data-action-type="ajax"
                    data-confirm="Confirmez-vous la suppression des éléments sélectionnés ?">
                    <i class="fas fa-trash-alt"></i> {{ __('Supprimer') }}
                    </button>
                    @endcan
                    </span>
                </div>
                @show
            </div>
        </div>
    </section>
     <section id="notification-data-container-out" >
        @if($notification_viewType == "widgets")
        @include("PkgNotification::notification._$notification_viewType")
        @endif
    </section>
    @show
</div>