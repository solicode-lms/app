{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('notification-table')
<div class="card-body p-0 crud-card-body" id="notifications-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                $bulkEdit = Auth::user()->can('edit-notification') || Auth::user()->can('destroy-notification');
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
                    $isEditable = Auth::user()->can('edit-notification') && Auth::user()->can('update', $notification);
                @endphp
                <tr id="notification-row-{{$notification->id}}" data-id="{{$notification->id}}">
                    <x-checkbox-row :item="$notification" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="title"  data-toggle="tooltip" title="{{ $notification->title }}" >
                    <x-field :entity="$notification" field="title">
                        {{ $notification->title }}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="message"  data-toggle="tooltip" title="{{ $notification->message }}" >
                    <x-field :entity="$notification" field="message">
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($notification->message, 30) !!}
                    </x-field>
                    </td>
                    <td style="max-width: 27.333333333333332%;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$notification->id}}" data-field="sent_at"  data-toggle="tooltip" title="{{ $notification->sent_at }}" >
                    <x-field :entity="$notification" field="sent_at">
                        {{ $notification->sent_at }}
                    </x-field>
                    </td>
                    <td class="text-right text-truncate" style="max-width: 15%;">


                       

                        @can('edit-notification')
                        <x-action-button :entity="$notification" actionName="edit">
                        @can('update', $notification)
                            <a href="{{ route('notifications.edit', ['notification' => $notification->id]) }}" data-id="{{$notification->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan
                        @can('show-notification')
                        <x-action-button :entity="$notification" actionName="show">
                        @can('view', $notification)
                            <a href="{{ route('notifications.show', ['notification' => $notification->id]) }}" data-id="{{$notification->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        </x-action-button>
                        @endcan

                        <x-action-button :entity="$notification" actionName="delete">
                        @can('destroy-notification')
                        @can('delete', $notification)
                            <form class="context-state" action="{{ route('notifications.destroy',['notification' => $notification->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger deleteEntity" data-id="{{$notification->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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