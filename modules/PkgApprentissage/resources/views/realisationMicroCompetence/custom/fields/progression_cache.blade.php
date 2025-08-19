@php
    $progression = $entity->progression_cache ?? 0;
    $progressionIdeal = $entity->progression_ideal_cache ?? 0;
    $tauxRythme = $entity->taux_rythme_cache; // peut être null

    // ✅ Segmentation : progression vs idéal
    $atteint = min($progression, $progressionIdeal);
    $retard = max(0, $progressionIdeal - $progression);
    $avance = max(0, $progression - $progressionIdeal);

    // seuil pour afficher le texte dans la barre
    $showTextThreshold = 12; // en % (~ largeur mini pour lisibilité)
@endphp

<div class="progress progress-sm" style="height: 0.75rem;">
    {{-- ✅ Progression atteinte (réelle jusqu'à l'idéal) --}}
    <div class="progress-bar bg-success" 
         style="width: {{ $atteint }}%;" 
         data-toggle="tooltip"
         title="Progression atteinte ({{ $atteint }}%)">
        @if($atteint > $showTextThreshold)
            {{ $atteint }}%
        @endif
    </div>

    {{-- ⚠️ Retard (idéal non atteint) --}}
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

<small class="text-muted">
    @if(!is_null($tauxRythme))
       ⚡ Rythme :  <strong data-toggle="tooltip" title="{{ $tauxRythme }}% Rythme">{{ $tauxRythme }}%</strong>
    @else
      ✔️ <strong data-toggle="tooltip" title="{{ $progression }}% Réel">{{ $progression }}%</strong>
    | 🎯 <strong data-toggle="tooltip" title="{{ $progressionIdeal }}% Idéal">{{ $progressionIdeal }}%</strong>
    @endif
</small>
