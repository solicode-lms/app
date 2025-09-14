{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetType-table')
<div class="card-body p-0 crud-card-body" id="widgetTypes-crud-card-body">
    <table class="table table-striped text-nowrap" style="table-layout: fixed; width: 100%;">
        <thead style="width: 100%">
            <tr>
                @php
                    $bulkEdit = $widgetTypes_permissions['edit-widgetType'] || $widgetTypes_permissions['destroy-widgetType'];
                @endphp
                <x-checkbox-header :bulkEdit="$bulkEdit" />
                <x-sortable-column :sortable="true" width="41"  field="type" modelname="widgetType" label="{!!ucfirst(__('PkgWidgets::widgetType.type'))!!}" />
                <x-sortable-column :sortable="true" width="41"  field="description" modelname="widgetType" label="{!!ucfirst(__('PkgWidgets::widgetType.description'))!!}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetType-table-tbody')
            @foreach ($widgetTypes_data as $widgetType)
                @php
                    $isEditable = $widgetTypes_permissions['edit-widgetType'] && $widgetTypes_permissionsByItem['update'][$widgetType->id];
                @endphp
                <tr id="widgetType-row-{{$widgetType->id}}" data-id="{{$widgetType->id}}">
                    <x-checkbox-row :item="$widgetType" :bulkEdit="$bulkEdit" />
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widgetType->id}}" data-field="type">
                        {{ $widgetType->type }}

                    </td>
                    <td style="max-width: 41%;white-space: normal;" class="{{ $isEditable ? 'editable-cell' : '' }} text-truncate" data-id="{{$widgetType->id}}" data-field="description">
                  
                        {!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($widgetType->description, 30) !!}
                   

                    </td>
                    <td class="text-right wrappable" style="max-width: 15%;">


                       

                        @if($widgetTypes_permissions['edit-widgetType'])
                        <x-action-button :entity="$widgetType" actionName="edit">
                        @if($widgetTypes_permissionsByItem['update'][$widgetType->id])
                            <a href="{{ route('widgetTypes.edit', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif
                        @if($widgetTypes_permissions['show-widgetType'])
                        <x-action-button :entity="$widgetType" actionName="show">
                        @if($widgetTypes_permissionsByItem['view'][$widgetType->id])
                            <a href="{{ route('widgetTypes.show', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        @endif
                        </x-action-button>
                        @endif

                        <x-action-button :entity="$widgetType" actionName="delete">
                        @if($widgetTypes_permissions['destroy-widgetType'])
                        @if($widgetTypes_permissionsByItem['delete'][$widgetType->id])
                            <form class="context-state" action="{{ route('widgetTypes.destroy',['widgetType' => $widgetType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-default d-none d-lg-inline deleteEntity" data-id="{{$widgetType->id}}">
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
    @section('widgetType-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetTypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>