{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('notification-table')
<div class="card-body p-0 crud-card-body" id="notifications-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $notifications_permissions['edit-notification'] || $devnotifications_permissions['destroy-notification'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="title" modelname="notification" label="{{ucfirst(__('PkgNotification::notification.title'))}}" />
                <x-sortable-column :sortable="false" width="27.333333333333332"  field="message" modelname="notification" label="{{ucfirst(__('PkgNotification::notification.message'))}}" />
                <x-sortable-column :sortable="true" width="27.333333333333332"  field="sent_at" modelname="notification" label="{{ucfirst(__('PkgNotification::notification.sent_at'))}}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('notification-table-tbody')
            @foreach ($notifications_data as $notification)
                @php
                    $isEditable = $notifications_permissions['edit-notification'] && $notifications_permissionsByItem['update'][$notification->id];
                @endphp
                <tr id="notification-row-{{$notification->id}}" data-id="{{$notification->id}}">
                    <x-checkbox-row :item="$notification" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="title"  data-toggle="tooltip" title="{{ $notification->title }}" >
                        {{ $notification->title }}

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="message"  data-toggle="tooltip" title="{{ $notification->message }}" >
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="message"  data-toggle="tooltip" title="{{ $notification->message }}" >
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($notification->message, 30) !!}
                    </td>

                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="sent_at"  data-toggle="tooltip" title="{{ $notification->sent_at }}" >
                        <x-deadline-display :value="$notification->sent_at" />
                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($notifications_permissions['edit-notification'])
                        <x-action-button :entity="$notification" actionName="edit">
                        @if($notifications_permissionsByItem['update'][$notification->id])
                            <a href="{{ route('notifications.edit', ['notification' => $notification->id]) }}" data-id="{{$notification->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($notifications_permissions['show-notification'])
                        <x-action-button :entity="$notification" actionName="show">
                        @if($notifications_permissionsByItem['view'][$notification->id])
                            <a href="{{ route('notifications.show', ['notification' => $notification->id]) }}" data-id="{{$notification->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$notification" actionName="delete">
                        @if($notifications_permissions['destroy-notification'])
                        @if($notifications_permissionsByItem['delete'][$notification->id])
                            <form class="context-state" action="{{ route('notifications.destroy',['notification' => $notification->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$notification->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                        @endif
                        </x-action-button>
                    </td>
                </tr>
            @endforeach
            @show
        </tbody>
    </table>
</div>
@show

<div class="card-footer">
    @section('notification-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $notifications_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>