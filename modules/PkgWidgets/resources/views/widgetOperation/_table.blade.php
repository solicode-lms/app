{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetOperation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetOperations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetOperation-table-tbody')
            @foreach ($widgetOperations_data as $widgetOperation)
                <tr id="widgetOperation-row-{{$widgetOperation->id}}">
                    <td class="text-right">

                        @can('show-widgetOperation')
                        @can('view', $widgetOperation)
                            <a href="{{ route('widgetOperations.show', ['widgetOperation' => $widgetOperation->id]) }}" data-id="{{$widgetOperation->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-widgetOperation')
                        @can('update', $widgetOperation)
                            <a href="{{ route('widgetOperations.edit', ['widgetOperation' => $widgetOperation->id]) }}" data-id="{{$widgetOperation->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widgetOperation')
                        @can('delete', $widgetOperation)
                            <form class="context-state" action="{{ route('widgetOperations.destroy',['widgetOperation' => $widgetOperation->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widgetOperation->id}}">
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
    @section('widgetOperation-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetOperations_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>