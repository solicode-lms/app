{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0 crud-table" id="widgetOperationsTable">
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


<div class="card-footer">

    <div class="d-md-flex justify-content-between align-items-center p-2">
        <div class="d-flex align-items-center mb-2 ml-2 mt-2">
            @can('import-widgetOperation')
                <form action="{{ route('widgetOperations.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                        <i class="fas fa-file-download"></i>
                        {{ __('Core::msg.import') }}
                    </label>
                    <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
                </form>
            @endcan
            @can('export-widgetOperation')
                <form class="">
                    <a href="{{ route('widgetOperations.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
                        <i class="fas fa-file-export"></i>
                        {{ __('Core::msg.export') }}</a>
                </form>
            @endcan
        </div>

        <ul class="pagination m-0 float-right">
            {{ $data->onEachSide(1)->links() }}
        </ul>
    </div>

    <script>
        function submitForm() {
            document.getElementById("importForm").submit();
        }
    </script>
</div>