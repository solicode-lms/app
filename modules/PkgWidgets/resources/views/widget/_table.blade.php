{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <th>{{ ucfirst(__('PkgWidgets::widget.name')) }}</th>
                <th>{{ ucfirst(__('PkgWidgets::widgetType.singular')) }}</th>
                <th>{{ ucfirst(__('Core::sysModel.singular')) }}</th>
                <th>{{ ucfirst(__('PkgWidgets::widgetOperation.singular')) }}</th>
                <th>{{ ucfirst(__('PkgWidgets::widget.icon')) }}</th>
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $widget)
                <tr>
                    <td>{{ $widget->name }}</td>
                    <td>{{ $widget->widgetType->type ?? '-' }}</td>
                    <td>{{ $widget->sysModel->id ?? '-' }}</td>
                    <td>{{ $widget->widgetOperation->operation ?? '-' }}</td>
                    <td>{{ $widget->icon }}</td>
                    <td class="text-center">
                        @can('show-widget')
                            <a href="{{ route('widgets.show', $widget) }}" class="btn btn-default btn-sm">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-widget')
                            <a href="{{ route('widgets.edit', $widget) }}" class="btn btn-sm btn-default">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-widget')
                            <form action="{{ route('widgets.destroy', $widget) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce widget ?')">
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

<div class="d-md-flex justify-content-between align-items-center p-2">
    <div class="d-flex align-items-center mb-2 ml-2 mt-2">
        @can('import-widget')
            <form action="{{ route('widgets.import') }}" method="post" class="mt-2" enctype="multipart/form-data"
                id="importForm">
                @csrf
                <label for="upload" class="btn btn-default btn-sm font-weight-normal">
                    <i class="fas fa-file-download"></i>
                    {{ __('Core::msg.import') }}
                </label>
                <input type="file" id="upload" name="file" style="display:none;" onchange="submitForm()" />
            </form>
        @endcan
        @can('export-widget')
            <form class="">
                <a href="{{ route('widgets.export') }}" class="btn btn-default btn-sm mt-0 mx-2">
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
