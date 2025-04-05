
@section('widgetUtilisateur-widgets')

<h2>Widgets</h2>

@section('widgetUtilisateur-table-tbody')
@foreach ($widgetUtilisateurs_data as $widgetUtilisateur)

    <div id="widgetUtilisateur-row-{{$widgetUtilisateur->id}}">
        <div>
            <span @if(strlen($widgetUtilisateur->ordre) > 40) 
                data-toggle="tooltip" 
                title="{{ $widgetUtilisateur->ordre }}" 
            @endif>
            {{ Str::limit($widgetUtilisateur->ordre, 40) }}
        </span>
        </div>
        <div>
            <span @if(strlen($widgetUtilisateur->widget) > 50) 
                data-toggle="tooltip" 
                title="{{ $widgetUtilisateur->widget }}" 
            @endif>
            {{ Str::limit($widgetUtilisateur->widget, 50) }}
        </span>
        </div>
        <div>
            <span @if(strlen($widgetUtilisateur->package) > 40) 
                data-toggle="tooltip" 
                title="{{ $widgetUtilisateur->package }}" 
            @endif>
            {{ Str::limit($widgetUtilisateur->package, 40) }}
        </span>
        </div>
        <div>
            <span @if(strlen($widgetUtilisateur->type) > 40) 
                data-toggle="tooltip" 
                title="{{ $widgetUtilisateur->type }}" 
            @endif>
            {{ Str::limit($widgetUtilisateur->type, 40) }}
        </span>
        </div>
        <div>
            <span class="{{ $widgetUtilisateur->visible ? 'text-success' : 'text-danger' }}">
                {{ $widgetUtilisateur->visible ? 'Oui' : 'Non' }}
            </span>
        </div>
        <div class="text-right">

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
        </div>
    </div>
@endforeach
@show
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