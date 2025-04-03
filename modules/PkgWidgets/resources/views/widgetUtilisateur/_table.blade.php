{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@section('widgetUtilisateur-table')
<div class="card-body table-responsive p-0 crud-card-body" id="widgetUtilisateurs-crud-card-body">
    <table class="table table-striped text-nowrap">
        <thead>
            <tr>
                <x-sortable-column field="widget_id" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widget.singular')) }}" />
                <x-sortable-column field="package" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.package')) }}" />
                <x-sortable-column field="type" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.type')) }}" />
                <x-sortable-column field="visible" modelname="widgetUtilisateur" label="{{ ucfirst(__('PkgWidgets::widgetUtilisateur.visible')) }}" />
                <th class="text-center">{{ __('Core::msg.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @section('widgetUtilisateur-table-tbody')
            @foreach ($widgetUtilisateurs_data as $widgetUtilisateur)
                <tr id="widgetUtilisateur-row-{{$widgetUtilisateur->id}}">
                    <td>
                     <span @if(strlen($widgetUtilisateur->widget) > 50) 
                            data-toggle="tooltip" 
                            title="{{ $widgetUtilisateur->widget }}" 
                        @endif>
                        {{ Str::limit($widgetUtilisateur->widget, 50) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($widgetUtilisateur->package) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $widgetUtilisateur->package }}" 
                        @endif>
                        {{ Str::limit($widgetUtilisateur->package, 40) }}
                    </span>
                    </td>
                    <td>
                     <span @if(strlen($widgetUtilisateur->type) > 40) 
                            data-toggle="tooltip" 
                            title="{{ $widgetUtilisateur->type }}" 
                        @endif>
                        {{ Str::limit($widgetUtilisateur->type, 40) }}
                    </span>
                    </td>
                    <td>
                        <span class="{{ $widgetUtilisateur->visible ? 'text-success' : 'text-danger' }}">
                            {{ $widgetUtilisateur->visible ? 'Oui' : 'Non' }}
                        </span>
                    </td>
                    <td class="text-right">

                        @can('show-widgetUtilisateur')
                        @can('view', $widgetUtilisateur)
                            <a href="{{ route('widgetUtilisateurs.show', ['widgetUtilisateur' => $widgetUtilisateur->id]) }}" data-id="{{$widgetUtilisateur->id}}" class="btn btn-default btn-sm context-state showEntity">
                                <i class="far fa-eye"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('edit-widgetUtilisateur')
                        @can('update', $widgetUtilisateur)
                            <a href="{{ route('widgetUtilisateurs.edit', ['widgetUtilisateur' => $widgetUtilisateur->id]) }}" data-id="{{$widgetUtilisateur->id}}" class="btn btn-sm btn-default context-state editEntity">
                                <i class="fas fa-pen-square"></i>
                            </a>
                        @endcan
                        @endcan
                        @can('destroy-widgetUtilisateur')
                        @can('delete', $widgetUtilisateur)
                            <form class="context-state" action="{{ route('widgetUtilisateurs.destroy',['widgetUtilisateur' => $widgetUtilisateur->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger deleteEntity" data-id="{{$widgetUtilisateur->id}}">
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
    @section('widgetUtilisateur-crud-pagination')
    <ul class="pagination m-0 d-flex justify-content-center">
        {{ $widgetUtilisateurs_data->onEachSide(1)->links() }}
    </ul>
    @show
</div>
<script>
    window.viewState = @json($viewState);
</script>