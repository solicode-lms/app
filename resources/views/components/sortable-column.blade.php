@props(['field','modelname', 'label', 'isSorted'])
<th>
    <a href="{{ $getSortUrl() }}" class="sortable-column" data-sort="{{$field}}">
        {{ $label }}
        @if ($isSorted())
            <i class="fas fa-sort-{{ $getSortDirection() === 'asc' ? 'up' : 'down' }}"></i>
        @else
            <i class="fas fa-sort"></i>
        @endif
    </a>
</th>
