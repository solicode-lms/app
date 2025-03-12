@php
    $bgStyle = str_starts_with($background, '#') ? "background-color: $background;" : "class=$background";
    $textColor = str_starts_with($color, '#') ? "color: $color;" : "text-$color";
@endphp
@if($text)
<span class="badge px-2 py-1 rounded {{ $textColor }}" style="{{ $bgStyle }} {{ $textColor }}">
    {{ Str::limit($text, 50) }}
</span>
@endif
