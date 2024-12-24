<div class="col-lg-3 col-6">
    <div class="small-box bg-{{ $widget->color }}">
        <div class="inner">
            <h3>{{ is_array($widget->data ?? null) ? 'Array' : ($widget->data ?? 'N/A') }}</h3>
            <p class="card-title">{{ $widget->name }}</p>
            <p class="card-text">{{ $widget->label }}</p>
        </div>
        <div class="icon">
            <i class="fas {{ $widget->icon }}"></i>
        </div>
        <a href="#" class="small-box-footer">Voir les détails <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>

