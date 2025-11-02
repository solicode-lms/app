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
            $display = $value->format('d/m/Y H:i'); // Date de fin atteinte
        } else {
            $diff = $value->diff($now);
            $jours = $diff->d;
            $heures = $diff->h;
            $mois = $diff->m;

            if ($mois > 0) {
                $parts[] = $mois . ' ' . Str::plural('mois', $mois); // "mois" reste inchangé au pluriel
            }
            if ($jours > 0) {
                $parts[] = $jours . ' ' . Str::plural('jour', $jours);
            }
            if ($heures > 0 || empty($parts)) {
                $parts[] = $heures . ' ' . Str::plural('heure', $heures);
            }

            $display = implode(' ', $parts);

           
        }
    }
@endphp

<span>
    {{ $display ?? '-' }}
</span>
