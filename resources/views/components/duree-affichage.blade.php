@php
    $duree = $heures; // reçu depuis le composant
    $annees = floor($duree / (24 * 365));
    $reste_apres_annees = $duree % (24 * 365);
    $jours = floor($reste_apres_annees / 24);
    $heures_restantes = $reste_apres_annees % 24;
@endphp

<span>
    @if ($annees > 0)
        {{ $annees }} {{ $annees == 1 ? 'année' : 'années' }}
    @endif

    @if ($jours > 0)
        {{ $annees > 0 ? ' ' : '' }}{{ $jours }} {{ $jours == 1 ? 'jour' : 'jours' }}
    @endif

    @if ($heures_restantes > 0)
        {{ ($annees > 0 || $jours > 0) ? ' ' : '' }}{{ $heures_restantes }} {{ $heures_restantes == 1 ? 'heure' : 'heures' }}
    @endif

    @if ($annees == 0 && $jours == 0 && $heures_restantes == 0)
        0 heure
    @endif
</span>
