{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

<div class="card-body table-responsive p-0" id="widgetsTable">
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
                    <td>{{ $widget->sysModel->name ?? '-' }}</td>
                    <td>{{ $widget->widgetOperation->operation ?? '-' }}</td>
                    <td>{{ $widget->icon }}</td>
                    <td class="text-center">
                        @can('show-widget')
                            <a href="{{ route('widgets.show', $widget) }}" data-id="{{$widget->id}}" class="btn btn-default btn-sm showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @can('edit-widget')
                            <a href="{{ route('widgets.edit', $widget) }}" data-id="{{$widget->id}}" class="btn btn-sm btn-default editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @can('destroy-widget')
                            <form action="{{ route('widgets.destroy', $widget) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widget->id}}"
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

