@php
    $note = $entity->note;
    $bareme = $entity->bareme_note;
    $pourcentage = ($bareme > 0) ? round(($note ?? 0) / $bareme * 100, 2) : null;
@endphp

@if ($bareme > 0)
    <span>
        {{ $note }} / {{ $bareme }}
        <span class="text-muted" style="margin-left: 6px;">
            ({{ $pourcentage }} %)
        </span>
    </span>
@else
    <span>—</span>
@endif
