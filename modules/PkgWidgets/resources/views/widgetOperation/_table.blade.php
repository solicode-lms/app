{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetOperation-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetOperations-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="operation" modelname="widgetOperation" label="{{ ucfirst(__('PkgWidgets::widgetOperation.operation')) }}" />
                <x-sortable-column field="description" modelname="widgetOperation" label="{{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetOperation-table-tbody')
            @foreach ($widgetOperations_data as $widgetOperation)
                <tr id="widgetOperation-row-{{$widgetOperation->id}}">
                    <td>
                     <span @if(strlen($widgetOperation->operation) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $widgetOperation->operation }}" 
                        @endif>
                        {{ Str::limit($widgetOperation->operation, 40) }}
                    </span>
                    </td>
                    <td>{!! \App\Helpers\TextHelper::formatHtmlWithLineBreaks($widgetOperation->description, 50) !!}</td>
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