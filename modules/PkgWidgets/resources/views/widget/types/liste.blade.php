<div class="card col-6">
    <div class="card-header bg-{{ $widget->color }} text-white">
        <h3 class="card-title"> <i class="fas {{ $widget->icon }}"></i> {{ $widget->name }} - {{ $widget->count }}</h3>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @foreach ($widget->data ?? [] as $row)
            <li class="list-group-item">{{$row}}</li>
            @endforeach
        </ul>
    </div>
</div>
