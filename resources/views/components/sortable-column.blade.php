@props(['field','modelname', 'label', 'isSorted','width'])
<th class="text-truncate"  style="@isset($width) width: {{ $width }}%;@endisset" >
    <a href="{{ $getSortUrl() }}" class="text-truncate sortable-column" data-sort="{{$field}}">
        {{ $label }}
        @if ($isSorted())
            <i class="fas fa-sort-{{ $getSortDirection() === 'asc' ? 'up' : 'down' }}"></i>
        @else
            <i class="fas fa-sort"></i>
        @endif
    </a>
</th>
