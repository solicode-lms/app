
<div class="col-lg-6 col-6">
    <div class="card">
        <div class="card-header  text-white" style="background-color:  {{ $widget->sysColor?->hex}}">
            <h3 class="card-title"> <i class="fas {{ $widget->icon }}"></i> {{ $widget->label }} - {{ $widget->count }}</h3>
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
