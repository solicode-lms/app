@props([
    'value' => null, // La valeur DateTime
])

@php
    use Carbon\Carbon;

    $display = null;

    if (is_string($value)) {
        $value = Carbon::parse($value);
    }

    if ($value instanceof \DateTimeInterface) {
        $now = now();
        $inPast = $value->lt($now); // Vérifie si dans le passé

        if ($inPast) {
            $display = $value->format('d/m/Y'); // Date de fin atteinte
        } else {
            $diff = $value->diff($now);
            $jours = $diff->d;
            $heures = $diff->h;

            $display = "{$jours} jours {$heures} heures";
        }
    }
@endphp

<span>
    {{ $display ?? '-' }}
</span>
