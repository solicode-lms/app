
<div class="widget col-lg-6 col-6" data-id="{{$widgetUtilisateur->id}}">
    <div class="card">
        <div class="card-header  text-white" style="background-color:  {{ $widget->sysColor?->hex}}">
            <h3 class="card-title"> 
                <i class="fas {{ $widget->icon }}"></i> {{ $widget->label }}
            </h3>
            <div class="card-tools ">
                <span title="Nombre des éléménts" class="badge badge-primary">{{ $widget->count }}</span>
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool text-white" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>

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
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach ($widget->data ?? [] as $row)
                <li class="list-group-item">{{$row}}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
