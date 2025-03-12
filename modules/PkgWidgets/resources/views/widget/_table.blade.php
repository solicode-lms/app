{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widget-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgets-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="name" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widget.name')) }}" />
                <x-sortable-column field="label" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widget.label')) }}" />
                <x-sortable-column field="model_id" modelname="widget" label="{{ ucfirst(__('Core::sysModel.singular')) }}" />
                <x-sortable-column field="type_id" modelname="widget" label="{{ ucfirst(__('PkgWidgets::widgetType.singular')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widget-table-tbody')
            @foreach ($widgets_data as $widget)
                <tr id="widget-row-{{$widget->id}}">
                    <td>
                     <span @if(strlen($widget->name) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $widget->name }}" 
                        @endif>
                        {{ Str::limit($widget->name, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($widget->label) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $widget->label }}" 
                        @endif>
                        {{ Str::limit($widget->label, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($widget->model) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $widget->model }}" 
                        @endif>
                        {{ Str::limit($widget->model, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($widget->type) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $widget->type }}" 
                        @endif>
                        {{ Str::limit($widget->type, 50) }}
                    </span>
                    </td>
                    <td class="text-right">

                        @can('show-widget')
                        @can('view', $widget)
                            <a href="{{ route('widgets.show', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-widget')
                        @can('update', $widget)
                            <a href="{{ route('widgets.edit', ['widget' => $widget->id]) }}" data-id="{{$widget->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widget')
                        @can('delete', $widget)
                            <form class="context-state" action="{{ route('widgets.destroy',['widget' => $widget->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widget->id}}">
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
    @section('widget-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgets_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>