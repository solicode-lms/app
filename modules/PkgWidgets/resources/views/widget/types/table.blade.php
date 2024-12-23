<div class="card">
    <div class="card-header bg-{{ $widget->color }} text-white">
        <i class="{{ $widget->icon }}"></i> {{ $widget->name }}
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
