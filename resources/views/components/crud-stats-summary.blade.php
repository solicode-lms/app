<div class="stats-summary d-flex align-items-center flex-wrap">
    <h5 class="mb-0 mr-3"><i class="{{ $icon ?? 'fas fa-chart-bar text-info' }}"></i></h5>
    @foreach ($stats as $stat)
        <span class="badge badge-info mr-2 p-1" style="margin-bottom: 4px">
            <i class="{{ $stat['icon'] ?? '' }}"></i> 
            {{ $stat['label'] }} : {{ $stat['value'] }}
        </span>
    @endforeach
</div>
