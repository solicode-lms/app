{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="widgetOperationsTable">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgWidgets::widgetOperation.operation')) }}</th>
                <th>{{ ucfirst(__('PkgWidgets::widgetOperation.description')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $widgetOperation)
                <tr>
                    <td>{{ $widgetOperation->operation }}</td>
                    <td>{{ $widgetOperation->description }}</td>
                    <td class="text-center">
                        @can('show-widgetOperation')
                            <a href="{{ route('widgetOperations.show', $widgetOperation) }}" data-id="{{$widgetOperation->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-widgetOperation')
                            <a href="{{ route('widgetOperations.edit', $widgetOperation) }}" data-id="{{$widgetOperation->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-widgetOperation')
                            <form action="{{ route('widgetOperations.destroy', $widgetOperation) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widgetOperation->id}}">
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

