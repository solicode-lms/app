{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetType-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetTypes-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetType-table-tbody')
            @foreach ($widgetTypes_data as $widgetType)
                <tr id="widgetType-row-{{$widgetType->id}}">
                    <td class="text-right">

                        @can('show-widgetType')
                        @can('view', $widgetType)
                            <a href="{{ route('widgetTypes.show', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-widgetType')
                        @can('update', $widgetType)
                            <a href="{{ route('widgetTypes.edit', ['widgetType' => $widgetType->id]) }}" data-id="{{$widgetType->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widgetType')
                        @can('delete', $widgetType)
                            <form class="context-state" action="{{ route('widgetTypes.destroy',['widgetType' => $widgetType->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widgetType->id}}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                        @endcan
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