
<div class="widget col-lg-6 col-6" data-id="{{$widgetUtilisateur->id}}">
    <div class="card">
        <div class="card-header  text-{{$widget?->sysColor?->textColor}}" style="background-color:  {{ $widget->sysColor?->hex}}">
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
