<div class="widget col-lg-6 col-6" data-id="{{$widgetUtilisateur->id}}">
    <div class="card">
        <div class="card-header  text-white" style="background-color:  {{ $widget->sysColor?->hex}}">
            <h3 class="card-title"> 
                <i class="fas {{ $widget->icon }}"></i> {{ $widget->label }}
            </h3>
            <div class="card-tools ">
                <span title="Nombre des éléménts" class="badge badge-info">{{ $widget->count }}</span>
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool text-white" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @if(!empty($widget->data) && isset($widget->data[0]))
                            @foreach(array_keys((array)$widget->data[0]) as $key)
                                <th>{{ ucfirst($key) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($widget->data ?? [] as $row)
                        <tr>
                            @foreach ((array)$row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>

