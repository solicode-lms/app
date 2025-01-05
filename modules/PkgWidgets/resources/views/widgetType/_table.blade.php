{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-card-body" id="widgetTypes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="type" label="{{ ucfirst(__('PkgWidgets::widgetType.type')) }}" />
                <x-sortable-column field="description" label="{{ ucfirst(__('PkgWidgets::widgetType.description')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($widgetTypes_data as $widgetType)
                <tr>
                    <td>@limit($widgetType->type, 80)</td>
                    <td>{!! $widgetType->description !!}</td>
                    <td class="text-right">
                        @can('show-widgetType')
                            <a href="{{ route('widgetTypes.show', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-widgetType')
                            <a href="{{ route('widgetTypes.edit', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-widgetType')
                            <form class="context-state" action="{{ route('widgetTypes.destroy',['widgetType' => $widgetType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widgetType->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="card-footer">
    @section('crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetTypes_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>