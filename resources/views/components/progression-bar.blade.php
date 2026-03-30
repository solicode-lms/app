@php
    $atteint = min($progression, $progressionIdeal);
    $retardbrut = max(0, $progressionIdeal - $progression);
    $avance = max(0, $progression - $progressionIdeal);
    $showTextThreshold = 12; // largeur min pour afficher texte %
    
    $nonValide = isset($pourcentageNonValide) ? $pourcentageNonValide : 0;
    $nonValide = min($nonValide, $retardbrut); // Le non valide est extrait du retard global
    $retard = max(0, $retardbrut - $nonValide);
@endphp

<div style="width: 100%">
    <div class="progress progress-sm" style="height: 0.85rem;">
        {{-- ✅ Progression atteinte (réelle jusqu'à l'idéal) --}}
        <div class="progress-bar bg-success" 
             style="width: {{ $atteint }}%;" 
             data-toggle="tooltip"
             title="Progression atteinte ({{ $atteint }}%)">
            @if($atteint > $showTextThreshold)
                {{ $atteint }}%
            @endif
        </div>

        {{-- ❌ Non valide --}}
        @if($nonValide > 0)
        <div class="progress-bar bg-danger" 
             style="width: {{ $nonValide }}%;" 
             data-toggle="tooltip"
             title="Tâches à corriger ({{ $nonValide }}%)">
            @if($nonValide > $showTextThreshold)
                {{ $nonValide }}%
            @endif
        </div>
        @endif

        {{-- ⚠️ Retard (idéal non atteint hors non valide) --}}
        @if($retard > 0)
        <div class="progress-bar bg-warning" 
             style="width: {{ $retard }}%;" 
             data-toggle="tooltip"
             title="Retard par rapport à l’idéal ({{ $retard }}%)">
            @if($retard > $showTextThreshold)
                -{{ $retard }}%
            @endif
        </div>
        @endif

        {{-- 🚀 Avance (au-delà de l’idéal) --}}
        @if($avance > 0)
        <div class="progress-bar bg-info" 
             style="width: {{ $avance }}%;" 
             title="Avance sur l’idéal ({{ $avance }}%)">
            @if($avance > $showTextThreshold)
                +{{ $avance }}%
            @endif
        </div>
        @endif
    </div>

    @if(isset($baremeNonEvalue) && $baremeNonEvalue > 0)
    <div class="mt-1" style="font-size: 0.75rem;">
        <span class="text-secondary" title="Total des barèmes des tâches en attente d'évaluation" data-toggle="tooltip">
            <i class="fas fa-hourglass-half text-warning"></i> À évaluer : <strong>{{ $baremeNonEvalue }} Pts</strong>
        </span>
    </div>
    @endif

    {{-- <small class="text-muted">
        @if(!is_null($tauxRythme))
           ⚡ Rythme : <strong data-toggle="tooltip" title="{{ $tauxRythme }}% Rythme">{{ $tauxRythme }}%</strong>
        @else
          ✔️ <strong data-toggle="tooltip" title="{{ $progression }}% Réel">{{ $progression }}%</strong>
        | 🎯 <strong data-toggle="tooltip" title="{{ $progressionIdeal }}% Idéal">{{ $progressionIdeal }}%</strong>
        @endif
    </small> --}}
</div>
